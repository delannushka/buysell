<?php

namespace app\models\forms;

class CommentForm extends \yii\base\Model
{
    public $comment;
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