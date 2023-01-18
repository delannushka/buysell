<?php

/** @var yii\web\View $this */
/** @var ActiveDataProvider $searchTicketsProvider */
/** @var ActiveDataProvider $freshTicketsProvider */

use yii\data\ActiveDataProvider;
use yii\widgets\ListView;

$this->title = 'Результаты поиска'
?>

<section class="search-results">
    <h1 class="visually-hidden">Результаты поиска</h1>
    <?=ListView::widget([
        'dataProvider' => $searchTicketsProvider,
        'itemView' => '/_list_ticket',
        'options' => [
            'tag' => 'div',
            'class' => 'search-results__wrapper'
        ],
        'summary' => 'Найдено <span class="js-results">{totalCount} публикации</span>',
        'layout' => "{summary}<ul class='search-results__list'>{items}</ul>",
        'summaryOptions' => [
            'tag' => 'p',
            'class' => 'search-results__label'
        ],
        'itemOptions' => [
            'tag' => false
        ],
        'emptyText' => 'Не найдено <br>ни&nbsp;одной публикации',
        'emptyTextOptions' => [
            'tag' => 'div',
            'class' => 'search-results__message'
        ]
    ]); ?>
</section>

<section class="tickets-list">
    <h2 class="visually-hidden">Самые новые предложения</h2>
    <div class="tickets-list__wrapper">
        <div class="tickets-list__header">
            <p class="tickets-list__title">Самое свежее</p>
        </div>
        <?=ListView::widget([
            'dataProvider' => $freshTicketsProvider,
            'options' => [
                'tag' => 'ul'
            ],
            'itemView' => '/_list_ticket',
            'itemOptions' => ['tag' => false],
            'layout' => '{items}'
        ]); ?>
    </div>
</section>
