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
use yii\web\Response;
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

    /**
     * Страница просмотра личных объявлений
     *
     * @return string - код страницы
     */
    public function actionIndex(): string
    {
        $id = Yii::$app->user->id;
        $myTicketsProvider = new ActiveDataProvider([
            'query' => Ticket::getMyTickets($id)
        ]);
        return $this->render('index', [
            'myTicketsProvider' => $myTicketsProvider
        ]);
    }

    /**
     * Страница просмотра личных объявлений с комментариями
     *
     * @return string - код страницы
     */
    public function actionComments(): string
    {
        $userId = Yii::$app->user->id;
        $ticketProvider = new ActiveDataProvider([
            'query' => Ticket::getMyTicketsWithComments($userId)
        ]);
        return $this->render('comments', [
            'ticketProvider' => $ticketProvider
        ]);
    }

    /**
     * Удаление выбранного объявления
     *
     * @param int $id - id объявления
     * @return Response - код страницы
     * @throws ForbiddenHttpException
     * @throws ServerErrorHttpException
     * @throws NotFoundHttpException
     */
    public function actionDelete(int $id): Response
    {
        $ticket = Ticket::findOne($id);
        if (!$ticket || $ticket->status === 0){
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
     * Удаление выбранного комментария
     *
     * @param int $id - id комментария
     * @return Response - код страницы
     * @throws ForbiddenHttpException
     * @throws ServerErrorHttpException
     * @throws NotFoundHttpException
     */
    public function actionCommentout(int $id): Response
    {
        $comment = Comment::findOne($id);
        if (!$comment || $comment->status === 0){
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