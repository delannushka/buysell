<?php

/** @var Ticket $model */

use app\models\Ticket;
use app\handlers\TicketHandler;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<li class="tickets-list__item">
    <div class="ticket-card ticket-card--color08">
        <div class="ticket-card__img">
            <img src="<?=Html::img(Yii::$app->urlManager->createUrl('uploads/tickets/' . $model->photo)); ?>" srcset="<?= Html::img(Yii::$app->urlManager->createUrl('uploads/tickets/' . $model->photo)); ?> 2x" alt="Изображение товара">
        </div>

        <div class="ticket-card__info">
            <span class="ticket-card__label"><?=TicketHandler::getLabel($model->type); ?></span>
            <div class="ticket-card__categories">
                <?php foreach ($model->categories as $category): ?>
                    <a href="<?=Url::to('/offers/category/' . $category->id) ;?>"><?=$category->name; ?></a>
                <?php endforeach; ?>
            </div>
            <div class="ticket-card__header">
                <h3 class="ticket-card__title"><a href="<?=Url::to('/offers/' . $model->id); ?>"><?=$model->header; ?></a></h3>
                <p class="ticket-card__price"><span class="js-sum"><?=$model->price; ?></span> ₽</p>
            </div>
            <div class="ticket-card__desc">
                <p><?=mb_strimwidth($model->text, 0, 55, "..."); ?></p>
            </div>
        </div>
    </div>
</li>