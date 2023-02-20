<?php

namespace app\handlers;

use app\models\User;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;
use Yii;
use yii\base\Model;

class NotificationHandler extends Model
{

    /**
     * Поиск непрочитанных сообщений
     *
     * @param array $allChatsData Массив в котором ищем
     * @param array $result Массив непрочитанных сообщений
     */
    public static function getUnreadMessages(array $allChatsData, array &$result)
    {
        // Если в массиве есть элемент с ключем 'read' и он false, закидываем в result
        if (isset($allChatsData['read']) && $allChatsData['read'] === false) {
            $result[] = $allChatsData;
        }
        // Обходим все элементы массива в цикле
        foreach ($allChatsData as $key => $param) {
            // Если элемент массива - массив, то вызываем рекурсивно эту функцию
            if (is_array($param)) {
                self::getUnreadMessages($param, $result);
            }
        }
    }

    /**
     * Поиск id пользователей, у которых есть непрочитанные сообщения
     *
     * @param array $dataUnreadMessages массив сообщений, из которого узнаем id пользователя
     * @return array
     */
    public static function getUsersForNotification(array $dataUnreadMessages): array
    {
        $users = [];
        foreach ($dataUnreadMessages as $dataUnreadMessage){
            $users[] = $dataUnreadMessage['recipient_id'];
        }
        return array_unique($users);
    }

    /**
     * Отправка письма пользователю, у которого есть непрочитанные сообщения
     * @param int $recipientId id пользователя, которому производится отправка
     *
     * @throws TransportExceptionInterface
     */
    public static function sendEmail(int $recipientId): void
    {
        $recipient = User::findOne($recipientId);

        // Конфигурация транспорта
        $dsn = 'smtp://annaselvyan:vdeisnmgryghjfrk@smtp.yandex.ru:465';
        $transport = Transport::fromDsn($dsn);

        $email = $recipient->email;
        $name = $recipient->name;

        //Формирование сообщения
        $message = new Email();

        $message->to("$email");
        $message->from(Yii::$app->params['buysellEmail']);
        $message->subject("Уведомление от сервиса «BuySell»");
        $message->text(
            "Уважаемый (ая), $name. У вас есть непрочитанные сообщения."
        );

        // Отправка сообщения
        $mailer = new Mailer($transport);
        $mailer->send($message);
    }
}