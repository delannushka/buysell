<?php

/** @var Ticket $model */

use app\models\Ticket;
use app\widgets\CommentWidget;
use delta\TicketHandler;
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
        <?php foreach ($model->comments as $comment) {
            if ($comment->status === 1) {
                echo CommentWidget::widget(['comment' => $comment]);
            }
        }?>
    </ul>
</div>
