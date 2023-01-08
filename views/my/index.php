<?php

use yii\widgets\ListView;

?>
<section class="tickets-list">
        <h2 class="visually-hidden">Самые новые предложения</h2>
        <div class="tickets-list__wrapper">
            <div class="tickets-list__header">
                <a href="<?=\yii\helpers\Url::to('/offers/add'); ?>" class="tickets-list__btn btn btn--big"><span>Новая публикация</span></a>
            </div>
            <?php if ($myTicketsProvider->totalCount !== 0): ?>
            <?= ListView::widget([
                'dataProvider' => $myTicketsProvider,
                'itemView' => '/_list_my_ticket',
                'options' => [
                    'tag' => 'ul'
                ],
                'layout' => '{items}',
                'itemOptions' => [
                    'tag' => false
                ]])
            ?>
<?php else: ?>

    <div class="search-results__message"> У вас нет <br>ни&nbsp;одной публикации </div>
            <?php endif; ?>
        </div>
    </section>

