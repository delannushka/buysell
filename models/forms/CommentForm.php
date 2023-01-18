<?php

namespace app\models\forms;

use yii\base\Model;

class CommentForm extends Model
{
    public string $comment;
    const MIN_TEXT = 20;

    public function attributeLabels(): array
    {
        return [
            'comment' => 'Текст комментария',
        ];
    }

    public function rules(): array
    {
        return [
            ['comment', 'required'],
            ['comment', 'string', 'min' => self::MIN_TEXT]
        ];
    }
}