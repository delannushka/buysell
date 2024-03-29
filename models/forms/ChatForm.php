<?php

namespace app\models\forms;

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

}