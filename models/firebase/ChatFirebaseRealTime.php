<?php
namespace app\models\firebase;

use yii\base\Model;
use Kreait\Firebase\Factory;

class ChatFirebaseRealTime extends Model
{
    function connectDb()
    {
        $factory = (new Factory)
            ->withServiceAccount(__DIR__ . '/buysell-ca35f-firebase-adminsdk-v8bwf-608e868ed7.json')
            ->withDatabaseUri('https://buysell-ca35f-default-rtdb.firebaseio.com');

        return $factory->createDatabase();
    }

    public function sendMessage($userId, string $message)
    {

    }
}

