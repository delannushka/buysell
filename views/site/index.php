<?php

/** @var yii\web\View $this */

use app\models\Ticket;
use yii\helpers\Url;
use yii\widgets\ListView;

$this->title = 'Куплю Продам';
?>

<?php if (!Ticket::find()->where(['status' => 1])->exists()):?>
    <div class="message">
        <div class="message__text">
            <p>На сайте еще не опубликовано ни&nbsp;одного объявления.</p>
        </div>
        <a href="<?= Url::to(['/login']) ?>" class="message__link btn btn--big">Вход и регистрация</a>
    </div>
<?php else: ?>
    <section class="categories-list">
        <h1 class="visually-hidden">Сервис объявлений "Куплю - продам"</h1>
            <?=ListView::widget([
                'dataProvider' => $mainCategoriesProvider,
                'options' => [
                    'tag' => 'ul',
                    'class' => 'categories-list__wrapper'
                ],
                'itemView' => '/_list_category',
                'itemOptions' => ['tag' => false],
                'layout' => '{items}'
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
    <?php if ($popularTicketsProvider->totalCount !== 0): ?>
        <section class="tickets-list">
            <h2 class="visually-hidden">Самые обсуждаемые предложения</h2>
            <div class="tickets-list__wrapper">
                <div class="tickets-list__header">
                    <p class="tickets-list__title">Самые обсуждаемые</p>
                </div>
                <?=ListView::widget([
                    'dataProvider' => $popularTicketsProvider,
                    'options' => [
                        'tag' => 'ul'
                    ],
                    'itemView' => '/_list_ticket',
                    'itemOptions' => ['tag' => false],
                    'layout' => '{items}',
                ]); ?>
            </div>
        </section>
    <?php endif; ?>
<?php endif; ?>
