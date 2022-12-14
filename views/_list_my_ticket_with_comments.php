<?php

use delta\TicketHandler;
use yii\helpers\Html;
use yii\helpers\Url;

?>
<div class="comments__block">
        <div class="comments__header">
            <a href="<?=Url::to('/offers/' . $model->id); ?>" class="announce-card">
                <h2 class="announce-card__title"><?=$model->header; ?></h2>
                <span class="announce-card__info">
              <span class="announce-card__price">₽ <?=$model->price; ?></span>
              <span class="announce-card__type"><?=TicketHandler::getLabel($model->type); ?></span>
            </span>
            </a>
        </div>

        <ul class="comments-list">
            <?php foreach ($model->comments as $comment): ?>
            <li class="js-card">
                <div class="comment-card">
                    <div class="comment-card__header">
                        <a href="#" class="comment-card__avatar avatar">
                            <img src="<?=Url::to('/uploads/' . $comment->user->avatar); ?>" srcset="<?=Url::to('/uploads/' . $comment->user->avatar); ?> 2x" alt="Аватар пользователя">
                        </a>
                        <p class="comment-card__author"><?=$comment->user->name; ?></p>
                    </div>
                    <div class="comment-card__content">
                        <p><?=$comment->text; ?></p>
                    </div>
                    <?=Html::a('Удалить', Url::to(['my/commentout/'. $comment->id]), ['class'=>'comment-card__delete js-delete']); ?>
                </div>
            </li>
            <?php endforeach; ?>
        </ul>
    </div>
