<?php

use delta\TicketHandler;
use yii\helpers\Html;
use yii\helpers\Url;

?>


<li class="tickets-list__item js-card">
    <div class="ticket-card ticket-card--color06">
        <div class="ticket-card__img">
            <img src="<?= Html::img(Yii::$app->urlManager->createUrl('uploads/tickets/' . $model->photo)) ?>" srcset="<?= Html::img(Yii::$app->urlManager->createUrl('uploads/tickets/' . $model->photo)) ?> 2x" alt="Изображение товара">
        </div>

        <div class="ticket-card__info">
            <span class="ticket-card__label"><?=TicketHandler::getLabel($model->type); ?></span>
            <div class="ticket-card__categories">
                <?php
                foreach ($model->categories as $category): ?>
                    <a href="<?=Url::to('/offers/category/' . $category->id) ;?>"><?=$category->name; ?></a>
                <?php
                endforeach; ?>
            </div>
            <div class="ticket-card__header">
                <h3 class="ticket-card__title"><a href="<?=Url::to('/offers/edit/' . $model->id); ?>"><?=$model->header; ?></a></h3>
                <p class="ticket-card__price"><span class="js-sum"><?=$model->price; ?></span> ₽</p>
            </div>
        </div>
        <?php
            echo Html::a('Удалить', Url::to(['my/delete/'. $model->id]), ['class'=>'ticket-card__del js-delete']);
        ?>
    </div>
</li>
