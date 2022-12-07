<?php

/** @var yii\web\View $this */

use yii\helpers\Url;

$this->title = 'Куплю Продам';
?>
<div class="message">
    <div class="message__text">
        <p>На сайте еще не опубликовано ни&nbsp;одного объявления.</p>
    </div>
    <a href="<?= Url::to(['/login']) ?>" class="message__link btn btn--big">Вход и регистрация</a>
</div>
