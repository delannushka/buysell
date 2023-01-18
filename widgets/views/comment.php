<?php

/** @var Comment $comment */

use app\models\Comment;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<li <?=(Yii::$app->request->url === '/my/comments') ? 'js-card' : '' ?>>
    <div class="comment-card">
        <div class="comment-card__header">
            <a href="#" class="comment-card__avatar avatar">
                <img src="<?=Url::to('/uploads/avatar/'. $comment->user->avatar); ?>" srcset="<?=Url::to('/uploads/avatar/'. $comment->user->avatar); ?> 2x" alt="Аватар пользователя">
            </a>
            <p class="comment-card__author"><?=$comment->user->name; ?></p>
        </div>
        <div class="comment-card__content">
            <p><?=$comment->text; ?></p>
        </div>
        <?php if (Yii::$app->request->url === '/my/comments'): ?>
            <?=Html::a('Удалить', Url::to(['my/commentout/'. $comment->id]), ['class'=>'comment-card__delete js-delete']); ?>
        <?php endif; ?>
    </div>
</li>
