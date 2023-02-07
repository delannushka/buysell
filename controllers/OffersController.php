<?php

namespace app\controllers;

use app\models\Category;
use app\models\Comment;
use app\models\forms\ChatForm;
use app\models\forms\CommentForm;
use app\models\forms\TicketForm;
use app\models\Ticket;
use delta\FirebaseHandler;
use Exception;
use Kreait\Firebase\Exception\DatabaseException;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;

class OffersController extends Controller
{
    const LIMIT_TICKETS = 8;
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['index', 'category'],
                        'roles'   => ['?', '@'],
                    ],
                    [
                        'allow'   => true,
                        'actions' => ['add', 'edit'],
                        'roles'   => ['@'],

                    ],
                ],
            ],
        ];
    }

    /**
     * Страница просмотра объявлений выбранной категории
     *
     * @param int $id - id объявления
     * @return Response|string - код страницы
     * @throws NotFoundHttpException
     * @throws Exception|DatabaseException
     */
    public function actionIndex(int $id): Response|string
    {
        $ticket = Ticket::findOne($id);
        if (!$ticket || $ticket->status === 0) {
            throw new NotFoundHttpException ('Объявление не найдено');
        }

        $authorTicket = $ticket->user;

        if (Yii::$app->user->id !== $authorTicket->id){
            $isBuyer = true;
        } else {
            $isBuyer = false;
        }
        if (Yii::$app->user->isGuest){
            $buyerId = null;
        }
        $newComment = new CommentForm();
        $chatForm = new ChatForm();
        $messages = [];

        //Текущий пользователь НЕ АВТОР объявления
        if ($isBuyer && !Yii::$app->user->isGuest) {
            $buyerId = Yii::$app->user->id;
            $database = new FirebaseHandler($id, $buyerId);
            $dataMessages = $database->getValue();
            if ($dataMessages !== null) {
                $messages = $database->extractData(true, $dataMessages);
                $database->getChatRead($messages);
            }
        }

        //Текущий пользователь АВТОР объявления
        if (!$isBuyer && !Yii::$app->user->isGuest) {
            $database = new FirebaseHandler($id);
            $dataMessages = $database->getValue();
            if ($dataMessages !== null) {
                $messages = $database->extractData(false, $dataMessages);
                $buyerId = $messages[0]['user_id'];
                $database = new FirebaseHandler($id, $buyerId);
                $dataMessages = $database->getValue();
                $messages = $database->extractData(true, $dataMessages);
                $database->getChatRead($messages);
            } else {
                $buyerId = null;
            }
        }

        if (Yii::$app->request->getIsAjax()) {
            $chatForm->load(Yii::$app->request->post());
            if ($chatForm->validate()) {
                $database = new FirebaseHandler($id, $buyerId);
                if ($buyerId === null) {
                    throw new ForbiddenHttpException('Чат должен начать покупатель');
                }
                $chatForm->addMessage($database);
                $renewDataMessages = $database->getValue();
                $messages = $database->extractData($buyerId, $renewDataMessages);
                $chatForm = new ChatForm();
            }
        }

        if (Yii::$app->request->post('submit_comment') === 'comment') {
            $newComment->load(Yii::$app->request->post());
            $comment = new Comment();
            if ($newComment->validate() && $comment->saveComment($ticket, $newComment->comment)) {
                return Yii::$app->response->redirect("/offers/{$id}");
            }
        }

        return $this->render('view', [
            'ticket' => $ticket,
            'model' => $newComment,
            'modelChat' => $chatForm,
            'messages' => $messages,
            'sellerId' => $authorTicket->id,
            'buyerId' => $buyerId
        ]);
    }

    /**
     * Страница с формой добавления нового объявления
     *
     * @return Response|string - код страницы
     * @throws ServerErrorHttpException
     */
    public function actionAdd(): Response|string
    {
        $newTicket = new TicketForm();
        if (Yii::$app->request->getIsPost()) {
            $newTicket->load(Yii::$app->request->post());
            $ticketId = $newTicket->createNewTicket();
            if ($ticketId){
                return Yii::$app->response->redirect("/offers/{$ticketId}");
            }
        }
        return $this->render('add-edit', ['model' => $newTicket]);
    }

    /**
     * Страница просмотра объявлений выбранной категории
     *
     * @param int $id - id категории
     * @return string - код страницы
     * @throws NotFoundHttpException|InvalidConfigException
     */
    public function actionCategory(int $id): string
    {
        $category = Category::findOne($id);
        if(!$category){
            throw new NotFoundHttpException('Категория не найдена');
        }

        $totalCountTickets = $category->getCountTicketsInCategory();
        $dataProvider = new ActiveDataProvider([
            'query' => Ticket::getTicketsInCategory($category->id),
            'totalCount' => $totalCountTickets,
            'pagination' => [
                'pageSize' => self::LIMIT_TICKETS,
                'pageSizeParam' => false,
                'forcePageParam' => false
            ]]
        );

        $categories = Category::getCategoriesList();
        return $this->render('category', [
            'dataProvider' => $dataProvider,
            'category' => $category,
            'categories' => $categories
        ]);
    }

    /**
     * Страница редактирования объявления
     *
     * @param int $id - id объявления
     * @return Response|string - код страницы
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException|ServerErrorHttpException
     */
    public function actionEdit(int $id): Response|string
    {
        $ticket = Ticket::findOne($id);
        if (!$ticket || $ticket->status === 0) {
            throw new NotFoundHttpException ('Объявление не найдено');
        }

        if (Yii::$app->user->can('editAllTickets', ['author_id' => $ticket->user_id])) {
            $ticketEditForm = new TicketForm();
            $ticketEditForm->autocompleteEditForm($ticket);
            if (Yii::$app->request->getIsPost()) {
                $ticketEditForm->load(Yii::$app->request->post());
                $ticketEditForm->editTicket($ticket);
                return Yii::$app->response->redirect("/offers/{$ticket->id}");
            }
            return $this->render('add-edit', ['model' => $ticketEditForm,]);
        } else {
            throw new ForbiddenHttpException ('Вам нельзя выполнять данное действие', 403);
        }
    }


}