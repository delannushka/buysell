<?php

namespace app\controllers;

use app\models\Comment;
use app\models\Ticket;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class MyController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => [],
                        'roles'   => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $id = Yii::$app->user->id;
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
     * @throws ForbiddenHttpException
     * @throws ServerErrorHttpException
     * @throws NotFoundHttpException
     */
    public function actionDelete($id){
        $ticket = Ticket::findOne($id);
        if (!$ticket){
            throw new NotFoundHttpException ('Объявление не найдено');
        }
        if (Yii::$app->user->can('editAllTickets', ['author_id' => $ticket->user_id])) {
            if ($ticket->deleteTicket()) {
                return $this->redirect('/my');
            } else {
                throw new ServerErrorHttpException ('Ошибка сервера', 500);
            }
        } else {
            throw new ForbiddenHttpException ('Вам нельзя выполнять данной действие', 403);
        }
    }


    /**
     * @throws ForbiddenHttpException
     * @throws ServerErrorHttpException
     * @throws NotFoundHttpException
     */
    public function actionCommentout($id){
        $comment = Comment::findOne($id);
        if (!$comment){
            throw new NotFoundHttpException ('Объявление не найдено');
        }
        if (Yii::$app->user->can('editAllTickets', ['author_id' => $comment->ticket->user_id])) {
            if ($comment->deleteComment()) {
                return $this->redirect('/my/comments');
            } else {
                throw new ServerErrorHttpException ('Ошибка сервера', 500);
            }
        } else {
            throw new ForbiddenHttpException ('Вам нельзя выполнять данное действие', 403);
        }
    }
}