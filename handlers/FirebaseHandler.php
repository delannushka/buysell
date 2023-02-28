<?php

namespace app\handlers;

use app\models\Ticket;
use Kreait\Firebase\Contract\Database;
use Kreait\Firebase\Database\Reference;
use Kreait\Firebase\Database\Snapshot;
use Kreait\Firebase\Exception\DatabaseException;
use Kreait\Firebase\Factory;
use Yii;
use yii\base\Model;

class FirebaseHandler extends Model
{
    private Database $realtimeDatabase;
    public int|null $ticketId;
    public int|null $buyerId;
    public Ticket $ticket;

    public function __construct($ticketId = null, $buyerId = null)
    {
        $this->ticketId         = $ticketId;
        $this->buyerId          = $buyerId;
        $this->realtimeDatabase = (new Factory())
            ->withServiceAccount(__DIR__.'/'
                .Yii::$app->params['namePrivateKeyFile'])
            ->withDatabaseUri(Yii::$app->params['nameDatabaseUri'])
            ->createDatabase();
        $this->ticket           = Ticket::findOne($ticketId);
    }

    /**
     * Получение пути для заданного чата
     *
     * @return ?string
     */
    public function getPathToChat(): ?string
    {
        if ( ! $this->buyerId) {
            return $this->ticketId;
        }

        return $this->ticketId.'/'.$this->buyerId;
    }

    /**
     * Получение данных чата
     *
     * @return ?array
     * @throws DatabaseException
     */
    public function getValue(): ?array
    {
        return $this->realtimeDatabase->getReference($this->getPathToChat())
            ->getValue();
    }

    /**
     * Получение Snapshot чата
     *
     * @return Snapshot
     * @throws DatabaseException
     */
    public function getSnap(): Snapshot
    {
        return $this->realtimeDatabase->getReference($this->getPathToChat())
            ->getSnapshot();
    }

    /**
     * Добавление сообщения в базу Firebase
     *
     * @param  string  $message  Сообщение отправленное в чат
     *
     * @return Reference
     * @throws DatabaseException
     */
    public function pushMessage(string $message): Reference
    {
        $thirdCoordinate = $this->getSnap()->numChildren();
        if (Yii::$app->user->id !== $this->ticket->user_id) {
            $recipientId = $this->ticket->user_id;
        } else {
            $recipientId = $this->buyerId;
        }

        return
            $this->realtimeDatabase->getReference($this->getPathToChat().'/'
                .$thirdCoordinate)
                ->set([
                    [
                        'user_id'      => Yii::$app->user->id,
                        'dt_add'       => date('c'),
                        'message'      => $message,
                        'read'         => false,
                        'recipient_id' => $recipientId,
                    ],
                ]);
    }

    /**
     * Получение тел сообщений из базы Firebase, для дальнейшей передачи во view
     *
     * @param  bool   $isBuyerExists  true - у объявления есть покупатель
     * @param  array  $dataMessages   Данные без обработки из Firebase
     *
     * @return array
     */
    public function extractData(bool $isBuyerExists, array $dataMessages): array
    {
        $messages = [];
        foreach ($dataMessages as $dataMessageFirst) {
            foreach ($dataMessageFirst as $dataMessageSecond) {
                if ( ! $isBuyerExists) {
                    foreach ($dataMessageSecond as $dataMessageThird) {
                        $messages[] = $dataMessageThird;
                    }
                } else {
                    $messages[] = $dataMessageSecond;
                }
            }
        }

        return $messages;
    }

    /**
     * Отметка сообщения как прочитанного
     *
     * @param  int  $messageNumber  номер сообщения в базе Firebase
     *
     * @return Reference
     * @throws DatabaseException
     */
    public function readMessage(int $messageNumber): Reference
    {
        $path = $this->getPathToChat().'/'.$messageNumber.'/0';

        return $this->realtimeDatabase->getReference($path)
            ->update([
                'read' => true,
            ]);
    }

    /**
     * Обновление статуса сообщений при просмотре
     *
     * @param  array  $messages  Сообщения, прочитанные пользователем
     *
     * @throws DatabaseException
     */
    public function getChatRead(array $messages): void
    {
        foreach ($messages as $key => $message) {
            if ($message['user_id'] !== Yii::$app->user->id) {
                $this->readMessage($key);
            }
        }
    }

    /**
     * Получение массива сообщений после отправки нового сообщения в чат
     *
     * @param  int|null  $buyerId  id покупателя, либо null, если чат пытается начать сам продавец
     * @param  string    $message  новое сообщение
     *
     * @return array массив сообщений чата
     * @throws DatabaseException
     */
    public function drawMessagesAfterPost(
        int|null $buyerId,
        string $message
    ): array {
        if ($buyerId === null) {
            echo 'Чат должен начать покупатель';
            exit();
        } else {
            $this->pushMessage($message);
            $renewDataMessages = $this->getValue();
            $messages          = $this->extractData($buyerId,
                $renewDataMessages);
        }

        return $messages;
    }

    /**
     * Получение массива сообщений, если текущий пользователь не автор объявления
     *
     * @return array массив сообщений чата
     * @throws DatabaseException
     */
    public function drawMessagesForBuyer(): array
    {
        $dataMessages = $this->getValue();
        if ($dataMessages !== null) {
            $messages = $this->extractData(true, $dataMessages);
            $this->getChatRead($messages);
        } else {
            $messages = [];
        }

        return $messages;
    }

    /**
     * Получение массива сообщений, если текущий пользователь автор объявления
     *
     * @param  int  $id  id объявления
     *
     * @return array массив сообщений чата
     * @throws DatabaseException
     */
    public function drawMessagesForAuthor(int $id): array
    {
        $dataMessages = $this->getValue();
        if ($dataMessages !== null) {
            $messages     = $this->extractData(false, $dataMessages);
            $buyerId      = $messages[0]['user_id'];
            $database     = new FirebaseHandler($id, $buyerId);
            $dataMessages = $database->getValue();
            $messages     = $database->extractData(true, $dataMessages);
            $database->getChatRead($messages);
        } else {
            $messages = [];
        }

        return $messages;
    }

    /**
     * Получение id покупателя, когда текущим пользователем является автор объявления
     *
     * @param  array  $messages  массив сообщений чата
     *
     * @return int|null
     */
    public function findBuyerId(array $messages): ?int
    {
        if (empty($messages)) {
            return null;
        } else {
            return $messages[0]['user_id'];
        }
    }

}