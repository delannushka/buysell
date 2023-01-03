<?php
/** @var yii\web\View $this */
?>

<section class="comments">
    <div class="comments__wrapper">
        <h1 class="visually-hidden">Страница комментариев</h1>
        <div class="comments__block">
            <div class="comments__header">
                <a href="#" class="announce-card">
                    <h2 class="announce-card__title">Ленд Ровер</h2>
                    <span class="announce-card__info">
              <span class="announce-card__price">₽ 900 000</span>
              <span class="announce-card__type">ПРОДАМ</span>
            </span>
                </a>
            </div>
            <ul class="comments-list">
                <li class="js-card">
                    <div class="comment-card">
                        <div class="comment-card__header">
                            <a href="#" class="comment-card__avatar avatar">
                                <img src="img/avatar03.jpg" srcset="img/avatar03@2x.jpg 2x" alt="Аватар пользователя">
                            </a>
                            <p class="comment-card__author">Александр Бурый</p>
                        </div>
                        <div class="comment-card__content">
                            <p>А что с прогоном автомобиля? Также вижу на фото зимнюю резину. А летняя идет ли впридачу?</p>
                        </div>
                        <button class="comment-card__delete js-delete" type="button">Удалить</button>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</section>