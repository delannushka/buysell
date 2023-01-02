<?php

use app\models\TicketCategory;
use yii\helpers\Url;

?>

<li class="categories-list__item">
    <a href="<?=Url::to('/offers/category/' . $model->id) ;?>" class="category-tile category-tile--default">
        <span class="category-tile__image">
            <img src="<?='uploads/tickets/' . $model->getRandomTitleImage(); ?>" srcset="<?=Url::to('/uploads/tickets/'. $model->getRandomTitleImage()) ;?> 2x" alt="Иконка категории">
        </span>
        <span class="category-tile__label"><?=$model->name; ?> <span class="category-tile__qty js-qty"><?=count($model->ticketCategories) ?></span></span>
    </a>
</li>