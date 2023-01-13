<?php

namespace app\widgets;

use app\models\Comment;
use yii\base\Widget;

class CommentWidget extends Widget
{
    public Comment $comment;

    public function run()
    {
        return $this->render('comment', ['comment' => $this->comment]);
    }
}