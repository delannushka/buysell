<?php

namespace app\controllers;

use app\models\Comment;
use app\models\forms\CommentForm;
use app\models\forms\LoginForm;
use app\models\forms\TicketForm;
use app\models\Ticket;
use app\models\TicketCategory;
use Exception;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;
use delta\UploadFile;

class OffersController extends \yii\web\Controller
{
    /**
     * @throws NotFoundHttpException
     * @throws Exception
     */
    public function actionIndex($id)
    {
        $newComment = new CommentForm();
        $ticket = Ticket::findOne($id);
        if (!$ticket) {
            throw new NotFoundHttpException('Объявление не найдено');
        }

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

                if ($ticket->save()) {
                    foreach ($newTicket->categories as $category) {
                        $ticketCategory = new TicketCategory();
                        $ticketCategory->ticket_id = $ticket->id;
                        $ticketCategory->category_id = $category;
                        $ticketCategory->save();
                    }
                    return Yii::$app->response->redirect("/offers/{$ticket->id}");
                }
            }
        }
        return $this->render('add.php', ['model' => $newTicket]);
    }
}