<?php

namespace app\controllers;

use app\models\Comment;
use app\models\Ticket;
use http\Url;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\ServerErrorHttpException;

class MyController extends \yii\web\Controller
{
    public function actionIndex(){

        $id = \Yii::$app->user->id;

        $myTicketsProvider = new ActiveDataProvider([
            'query' => Ticket::find()
            ->where([
                'user_id' => $id,
                'status' => 1
            ])
        ]);


        return $this->render('index', [
            'myTicketsProvider' => $myTicketsProvider
        ]);
    }

    public function actionComments()
    {
        $userId = Yii::$app->user->id;
        $ticketProvider = new ActiveDataProvider([
            'query' => Ticket::find()
                ->leftJoin('comment', 'comment.ticket_id = ticket.id')
                ->where([
                    'ticket.user_id' => $userId,
                    'ticket.status' => 1,
                    'comment.status' => 1
                ])
                ->groupBy('ticket.id')
                ->having('COUNT(comment.id) > 0')
                ->orderBy('MAX(comment.id) DESC')
        ]);
        return $this->render('comments', [
            'ticketProvider' => $ticketProvider
        ]);
    }

    /**
     * @throws \yii\db\StaleObjectException
     * @throws \Throwable
     */
    public function actionDelete($id){
        $ticket = Ticket::findOne($id);
        if ($ticket->deleteTicket()){
            return $this->redirect('/my');
        } else {
            throw new \Exception('Не удалось удалить объявдение');
        }
    }

    /**
     * @throws \Exception
     */
    public function actionCommentout($id){
        $comment = Comment::findOne($id);
        if ($comment->deleteComment()){
            return $this->redirect('/my/comments');
        } else {
            throw new \Exception('Не удалось удалить комментарий');
        }
    }
}