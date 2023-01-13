<?php
/** @var yii\web\View $this */
/** @var ActiveDataProvider $ticketProvider */

use yii\data\ActiveDataProvider;
use yii\widgets\ListView;

$this->title = 'Комментарии к публикациям'
?>

<section class="comments">
    <div class="comments__wrapper">
        <h1 class="visually-hidden">Страница комментариев</h1>
        <?= ListView::widget([
            'dataProvider' => $ticketProvider,
            'itemView' => '/_list_my_ticket_with_comments',
            'options' => [
                'tag' => 'div',
                'class' => 'comments__block'
            ],
            'layout' => '{items}',
            'itemOptions' => [
                'tag' => false
            ],
            'emptyText' => 'У ваших публикаций еще нет комментариев.',
            'emptyTextOptions' => [
                'tag' => 'p',
                'class' => 'comments__message'
            ]
        ]); ?>
    </div>
</section>