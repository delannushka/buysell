<?php

/** @var Ticket $ticket */

use app\models\Ticket;
use app\handlers\TicketHandler;
use yii\helpers\Url;
?>

<div class="ticket__img">
    <img src="<?=Url::to('/uploads/tickets/'. $ticket->photo); ?>" srcset="<?=Url::to('/uploads/tickets/'. $ticket->photo); ?> 2x" alt="Изображение товара">
</div>
<div class="ticket__info">
    <h2 class="ticket__title"><?=$ticket->header; ?></h2>
    <div class="ticket__header">
        <p class="ticket__price"><span class="js-sum"><?=$ticket->price; ?></span> ₽</p>
        <p class="ticket__action"><?= TicketHandler::getLabel($ticket->type); ?></p>
    </div>
    <div class="ticket__desc">
        <p><?=$ticket->text; ?></p>
    </div>
    <div class="ticket__data">
        <p>
            <b>Дата добавления:</b>
            <span><?=date('d F Y', strtotime($ticket->date_add)); ?></span>
        </p>
        <p>
            <b>Автор:</b>
            <a href="#"><?=$ticket->user->name; ?></a>
        </p>
        <p>
            <b>Контакты:</b>
            <a href="mailto:shkatulkin@ya.ru"><?=$ticket->user->email; ?></a>
        </p>
    </div>
    <ul class="ticket__tags">
        <?php foreach ($ticket->categories as $category): ?>
            <li>
                <a href="<?=Url::to('/offers/category/' . $category->id); ?>" class="category-tile category-tile--small">
                    <span class="category-tile__image">
                        <img src="<?=Url::to('/uploads/tickets/'. $category->getRandomTitleImage()); ?>" srcset = "<?=Url::to('/uploads/tickets/'. $category->getRandomTitleImage()); ?> 2x " alt="Иконка категории">
                    </span>
                    <span class="category-tile__label"><?=$category->name; ?></span>
                </a>
            </li>
        <?php endforeach; ?>
    </ul>
</div>

