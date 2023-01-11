<?php

/** @var yii\web\View $this */
/** @var yii\web\View $content */

use app\assets\AppAsset;
use yii\bootstrap5\Html;
use yii\helpers\Url;

AppAsset::register($this);
$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => "Доска объявлений — современный веб-сайт, упрощающий продажу или покупку абсолютно любых вещей." ?? '']);
// пока закомментрую
//$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/img/favicon.ico')]);?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<header class="header <?php if (!Yii::$app->user->isGuest): echo 'header--logged'; endif; ?>">
    <div class="header__wrapper">
        <a class="header__logo logo" href="<?=Url::to(['/']) ?>">
            <img src="<?=Yii::$app->request->baseUrl; ?>/img/logo.svg" width="179" height="34" alt="Логотип Куплю Продам">
        </a>
        <nav class="header__user-menu">
            <ul class="header__list">
                <li class="header__item">
                    <a href="<?=URL::to('/my'); ?>">Публикации</a>
                </li>
                <li class="header__item">
                    <a href="<?=URL::to('/my/comments'); ?>">Комментарии</a>
                </li>
            </ul>
        </nav>
        <form class="search" method="get" action="/search" autocomplete="off">
            <input type="search" name="query" placeholder="Поиск" aria-label="Поиск" value="<?=Yii::$app->request->get('query') ?? ''; ?>">
            <div class="search__icon"></div>
            <div class="search__close-btn"></div>
        </form>
        <?php if (!Yii::$app->user->isGuest):?>
        <a class="header__avatar avatar" href="#">
            <img src="<?=Url::to('/uploads/avatar/'. Yii::$app->user->identity->avatar); ?>" srcset="<?=Url::to('/uploads/avatar/'. Yii::$app->user->identity->avatar) ;?>" alt="Аватар пользователя">
        </a>
        <?php endif; ?>
        <a class="header__input" href="<?=URL::to('/login') ?>">Вход и регистрация</a>
    </div>
</header>

<main class="page-content">
    <?= $content ?>
</main>

<footer class="page-footer">
    <div class="page-footer__wrapper">
        <div class="page-footer__col">
            <a href="#" class="page-footer__logo-academy" aria-label="Ссылка на сайт HTML-Академии">
                <svg width="132" height="46">
                    <use xlink:href="img/sprite_auto.svg#logo-htmlac"></use>
                </svg>
            </a>
            <p class="page-footer__copyright">© 2019 Проект Академии</p>
        </div>
        <div class="page-footer__col">
            <a href="<?=Url::to(['/']) ?>" class="page-footer__logo logo">
                <img src="<?=Yii::$app->request->baseUrl; ?>/img/logo.svg" width="179" height="35" alt="Логотип Куплю Продам">
            </a>
        </div>
        <div class="page-footer__col">
            <ul class="page-footer__nav">
                <?php if (Yii::$app->user->isGuest): ?>
                <li>
                    <a href="<?=URL::to('/login') ?>">Вход и регистрация</a>
                </li>
                <?php else: ?>
                <li>
                    <a href="<?=URL::to('/login/logout') ?>">Выйти</a>
                </li>
                <?php endif; ?>
                <li>
                    <a href="<?=URL::to('/offers/add') ?>">Создать объявление</a>
                </li>
            </ul>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
