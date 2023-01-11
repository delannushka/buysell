<?php

namespace app\controllers;

use app\models\Category;
use app\models\Comment;
use app\models\forms\CommentForm;
use app\models\forms\TicketForm;
use app\models\Ticket;
use app\models\TicketCategory;
use Exception;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;
use delta\UploadFile;

class OffersController extends Controller
{
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
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function actionIndex($id)
    {
        $ticket = Ticket::findOne($id);
        if (!$ticket) {
            throw new NotFoundHttpException ('Объявление не найдено');
        }
        $newComment = new CommentForm();
        if (Yii::$app->request->getIsPost()) {
            $newComment->load(Yii::$app->request->post());
            if ($newComment->validate()){
                $comment = new Comment();
                $comment->user_id = Yii::$app->user->id;
                $comment->ticket_id = $ticket->id;
                $comment->text = $newComment->comment;
                if (!$comment->save()){
                    throw new Exception('Ошибка загрузки');
                }
            }
        }
        return $this->render('view', [
            'ticket' => $ticket,
            'model' => $newComment,
        ]);
    }

    /**
     * @throws Exception
     */
    public function actionAdd()
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
     * @throws NotFoundHttpException|InvalidConfigException
     */
    public function actionCategory($id){
        $category = Category::findOne($id);
        if(!$category){
            throw new NotFoundHttpException('Категория не найдена');
        }
        $totalCountTickets = $category->getCountTicketsInCategory();
        $dataProvider = new ActiveDataProvider([
            'query' => Ticket::find()
                ->select('id, status, header, photo, price, type, text, ticket_category.category_id as category_id')
                ->leftJoin('ticket_category', 'ticket_category.ticket_id = ticket_id')
                ->having('ticket.status = 1 and ticket_category.category_id = ' . $category->id),
            'totalCount' => $totalCountTickets,
            'pagination' => [
                'pageSize' => 1,
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
     * @throws Exception
     */

    public function actionEdit($id)
    {
        $ticket = Ticket::findOne($id);

        $ticketEditForm = new TicketForm();
        $ticketEditForm->autocompleteEditForm($ticket);
        if (Yii::$app->request->getIsPost()) {
            $ticketEditForm->load(Yii::$app->request->post());
            $ticketEditForm->editTicket($ticket);
            return Yii::$app->response->redirect("/offers/{$ticket->id}");
        }
        return $this->render('add-edit', ['model' => $ticketEditForm,]);
    }
}