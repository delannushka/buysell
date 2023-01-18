<?php

/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */
/** @var Exception$exception */

use yii\helpers\Url;

$this->context->layout = 'error';
$this->title = $name;
$statusCode = $exception->statusCode;
?>

<section class="error">
    <h1 class="error__title"><?=$statusCode; ?></h1>
    <h2 class="error__subtitle"><?=$message; ?></h2>
    <ul class="error__list">
        <?php if (Yii::$app->user->isGuest): ?>
        <li class="error__item">
            <a href="<?=Url::to('/login'); ?>">Вход и регистрация</a>
        </li>
        <?php else: ?>
        <li class="error__item">
            <a href="<?=Url::to('/offers/add'); ?>">Новая публикация</a>
        </li>
        <?php endif; ?>
        <li class="error__item">
            <a href="<?=Url::to('/'); ?>">Главная страница</a>
        </li>
    </ul>
    <form class="error__search search search--small" method="get" action="/search" autocomplete="off">
        <input type="search" name="query" placeholder="Поиск" aria-label="Поиск" value="<?=Yii::$app->request->get('query') ?? ''; ?>">
        <div class="search__icon"></div>
        <div class="search__close-btn"></div>
    </form>
    <a class="error__logo logo" href="<?=Url::to('/'); ?>">
        <img src="<?=Yii::$app->request->baseUrl; ?> /img/logo.svg" width="179" height="34" alt="Логотип Куплю Продам">
    </a>
</section>