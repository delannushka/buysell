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
            $newTicket->avatar = UploadedFile::getInstance($newTicket, 'avatar');

            if ($newTicket->validate()) {
                $ticket = new Ticket();
                $ticket->user_id = Yii::$app->user->getId();
                $ticket->header = $newTicket->header;
                $ticket->text = $newTicket->text;
                $ticket->price = $newTicket->price;
                $ticket->type = $newTicket->type;
                $ticket->photo = UploadFile::upload($newTicket->avatar, 'tickets');
                $db = Yii::$app->db;
                $transaction = $db->beginTransaction();
                try {
                    $ticket->save();
                    foreach ($newTicket->categories as $category) {
                        $ticketCategory = new TicketCategory();
                        $ticketCategory->ticket_id = $ticket->id;
                        $ticketCategory->category_id = $category;
                        $ticketCategory->save();
                    }
                    $transaction->commit();
                } catch (Exception $e) {
                    $transaction->rollback();
                    throw new ServerErrorHttpException('Проблема на сервере. Создать объявление не удалось.');
                }
                return Yii::$app->response->redirect("/offers/{$ticket->id}");
            }
        }
        return $this->render('add-edit', ['model' => $newTicket]);
    }

    /**
     * @throws NotFoundHttpException
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
        if(!$ticket){
            throw new NotFoundHttpException ('Объявление не найдено');
        }
        if (Yii::$app->user->can('editAllTickets', ['author_id' => $ticket->user_id])) {
            $ticketEditForm = new TicketForm();
            $ticketEditForm->header = $ticket->header;
            $ticketEditForm->text = $ticket->text;
            $ticketEditForm->price = $ticket->price;
            $ticketEditForm->type = $ticket->type;
            $ticketEditForm->categories = TicketCategory::find()->select('category_id')->where(
                ['ticket_id' => $ticket->id]
            )->column();
            $ticketEditForm->avatar = $ticket->photo;

            if (Yii::$app->request->getIsPost()) {
                $ticketEditForm->load(Yii::$app->request->post());
                if ($ticketEditForm->avatar !== $ticket->photo) {
                    $ticketEditForm->avatar = UploadedFile::getInstance($ticketEditForm, 'avatar');
                }
                if ($ticketEditForm->validate()) {
                    $ticket->header = $ticketEditForm->header;
                    $ticket->text = $ticketEditForm->text;
                    $ticket->price = $ticketEditForm->price;
                    $ticket->type = $ticketEditForm->type;
                    if ($ticketEditForm->avatar !== $ticket->photo) {
                        $ticket->photo = UploadFile::upload($ticketEditForm->avatar, 'tickets');
                    }
                    $db = Yii::$app->db;
                    $transaction = $db->beginTransaction();
                    try {
                        $ticket->save();
                        TicketCategory::deleteAll(['ticket_id' => $ticket->id]);
                        foreach ($ticketEditForm->categories as $category) {
                            $ticketCategory = new TicketCategory();
                            $ticketCategory->ticket_id = $ticket->id;
                            $ticketCategory->category_id = $category;
                            $ticketCategory->save();
                        }
                        $transaction->commit();
                    } catch (Exception $e) {
                        $transaction->rollback();
                        throw new ServerErrorHttpException('Проблема на сервере. Отредактировать объявление не удалось.');
                    }
                    return Yii::$app->response->redirect("/offers/{$ticket->id}");
                }
            }
            return $this->render('add-edit', ['model' => $ticketEditForm]);
        } else {
            throw new ForbiddenHttpException ('Вам нельзя выполнять данное действие', 403);
        }
    }
}