<?php

namespace delta;

use app\models\Ticket;
use Kreait\Firebase\Database\Reference;
use Kreait\Firebase\Database\Snapshot;
use Kreait\Firebase\Exception\DatabaseException;
use Kreait\Firebase\Factory;
use yii\base\Model;

class FirebaseHandler extends Model
{
    private $realtimeDatabase;
    public $sellerId;
    public $ticketId;
    public $buyerId;
    public $ticket;

    public function __construct($ticketId, $buyerId)
    {
        $this->ticketId = $ticketId;
        $this->buyerId = $buyerId;
        $this->realtimeDatabase = (new Factory())
            ->withServiceAccount(__DIR__ . '/buysell-ca35f-firebase-adminsdk-v8bwf-608e868ed7.json')
            ->withDatabaseUri('https://buysell-ca35f-default-rtdb.firebaseio.com')
            ->createDatabase();
        $this->ticket = Ticket::findOne($ticketId);
        $this->sellerId = $this->ticket->user_id;
    }

    public function getPathToChat(): string
    {
        return $this->ticketId . '/' . $this->buyerId;
    }

    /**
     * @throws DatabaseException
     */
    public function getValue(): array|bool|null
    {
        if (!$this->realtimeDatabase) {
            return false;
        }
        return $this->realtimeDatabase->getReference($this->getPathToChat())->getValue();
    }

    /**
     * @throws DatabaseException
     */
    public function getSnap(): Snapshot|bool
    {
        if (!$this->realtimeDatabase) {
            return false;
        }
        return $this->realtimeDatabase->getReference($this->getPathToChat())->getSnapshot();
    }

    /**
     * @throws DatabaseException
     */
    public function pushMessage(string $message): Reference|bool
    {
        if (!$this->realtimeDatabase || !$message) {
            return false;
        }
        $thirdCoordinate = $this->getSnap()->numChildren();
        return
            $this->realtimeDatabase->getReference($this->getPathToChat() . '/' . $thirdCoordinate)
            ->set([
                [
                    'user_id' => $this->buyerId,
                    'dt_add' => date('c'),
                    'message' => $message
                ]
            ]);
    }

    public function extractData($dataMassages)
    {
        $messages = [];
        foreach ($dataMassages as $dataMessage) {
            foreach ($dataMessage as $message) {
                $messages[] = $message;
            }
        }
        return $messages;
    }
}