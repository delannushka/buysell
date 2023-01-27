<?php

namespace app\models\forms;

use delta\FirebaseHandler;
use Kreait\Firebase\Exception\DatabaseException;
use yii\base\Model;

class ChatForm extends Model
{
    public string $message = '';

    /**
     * @inheritDoc
     */
    public function rules(): array
    {
        return [
            ['message', 'required'],
            ['message', 'string'],
        ];
    }

    /**
     * @inheritDoc
     */
    public function attributeLabels()
    {
        return [
            'message' => 'Введите сообщение в чат',
        ];
    }

    /**
     * Метод сохранения данных из формы добавления публикации в БД
     **
     * @param FirebaseHandler $chatFirebaseRealTime
     * @return bool
     * @throws DatabaseException
     */

    public function addMessage(FirebaseHandler $chatFirebaseRealTime): bool
    {
        if (!$this->message) {
            return false;
        }
        if ($chatFirebaseRealTime->pushMessage($this->message)) {
            return true;
        }
        return false;
    }
}