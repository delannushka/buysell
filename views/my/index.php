<?php

use yii\widgets\ListView;

?>
<section class="tickets-list">
        <h2 class="visually-hidden">Самые новые предложения</h2>
        <div class="tickets-list__wrapper">
            <div class="tickets-list__header">
                <a href="<?=\yii\helpers\Url::to('/offers/add'); ?>" class="tickets-list__btn btn btn--big"><span>Новая публикация</span></a>
            </div>

            <?= ListView::widget([
                'dataProvider' => $myTicketsProvider,
                'itemView' => '/_list_my_ticket',
                'options' => [
                    'tag' => 'ul'
                ],
                'layout' => '{items}',
                'itemOptions' => [
                    'tag' => false
                ],
                'emptyText' => 'Не найдено <br>ни&nbsp;одной публикации',
                'emptyTextOptions' => [
                    'tag' => 'div',
                    'class' => 'search-results__message'
                ]
            ])
            ?>
        </div>
    </section>

