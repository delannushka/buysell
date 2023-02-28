<?php

namespace app\controllers;

use app\handlers\FirebaseHandler;
use app\handlers\NotificationHandler;
use Kreait\Firebase\Exception\DatabaseException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use yii\filters\AccessControl;
use yii\web\Controller;

class NotificationController extends Controller
{
    /**
     * {@inheritdoc}
     */

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only'  => ['index'],
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['index'],
                        'roles'   => ['moderator'],
                    ],
                ],
            ],
        ];
    }


    /**
     * Отправка email уведомлений пользователям с непрочитаннымим сообщениями
     *
     * @return string - код страницы
     * @throws DatabaseException
     * @throws TransportExceptionInterface
     */

    public function actionIndex(): string
    {
        $firebase                = new FirebaseHandler();
        $firebaseAllTicketsChats = $firebase->getValue();

        $result = [];
        NotificationHandler::getUnreadMessages($firebaseAllTicketsChats,
            $result);

        $users = NotificationHandler::getUsersForNotification($result);

        foreach ($users as $key => $recipientId) {
            NotificationHandler::sendEmail($recipientId);
        }

        return $this->render('index');
    }
}