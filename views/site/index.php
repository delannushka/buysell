<?php

/** @var yii\web\View $this */

use app\models\Ticket;
use yii\helpers\Url;

$this->title = 'Куплю Продам';
?>
<?php if (!Ticket::find()->exists()):?>
<div class="message">
    <div class="message__text">
        <p>На сайте еще не опубликовано ни&nbsp;одного объявления.</p>
    </div>
    <a href="<?= Url::to(['/login']) ?>" class="message__link btn btn--big">Вход и регистрация</a>
</div>
<?php else: ?>
    <section class="categories-list">
        <h1 class="visually-hidden">Сервис объявлений "Куплю - продам"</h1>
        <ul class="categories-list__wrapper">
            <li class="categories-list__item">
                <a href="#" class="category-tile category-tile--default">
          <span class="category-tile__image">
            <img src="img/cat.jpg" srcset="img/cat@2x.jpg 2x" alt="Иконка категории">
          </span>
                    <span class="category-tile__label">Дом <span class="category-tile__qty js-qty">81</span></span>
                </a>
            </li>
            <li class="categories-list__item">
                <a href="#" class="category-tile category-tile--default">
          <span class="category-tile__image">
            <img src="img/cat02.jpg" srcset="img/cat02@2x.jpg 2x" alt="Иконка категории">
          </span>
                    <span class="category-tile__label">Электроника <span class="category-tile__qty js-qty">62</span></span>
                </a>
            </li>
            <li class="categories-list__item">
                <a href="#" class="category-tile category-tile--default">
          <span class="category-tile__image">
            <img src="img/cat03.jpg" srcset="img/cat03@2x.jpg 2x" alt="Иконка категории">
          </span>
                    <span class="category-tile__label">Одежда <span class="category-tile__qty js-qty">106</span></span>
                </a>
            </li>
            <li class="categories-list__item">
                <a href="#" class="category-tile category-tile--default">
          <span class="category-tile__image">
            <img src="img/cat04.jpg" srcset="img/cat04@2x.jpg 2x" alt="Иконка категории">
          </span>
                    <span class="category-tile__label">Спорт/отдых <span class="category-tile__qty js-qty">86</span></span>
                </a>
            </li>
            <li class="categories-list__item">
                <a href="#" class="category-tile category-tile--default">
          <span class="category-tile__image">
            <img src="img/cat05.jpg" srcset="img/cat05@2x.jpg 2x" alt="Иконка категории">
          </span>
                    <span class="category-tile__label">Авто <span class="category-tile__qty js-qty">34</span></span>
                </a>
            </li>
            <li class="categories-list__item">
                <a href="#" class="category-tile category-tile--default">
          <span class="category-tile__image">
            <img src="img/cat06.jpg" srcset="img/cat06@2x.jpg 2x" alt="Иконка категории">
          </span>
                    <span class="category-tile__label">Книги <span class="category-tile__qty js-qty">92</span></span>
                </a>
            </li>
        </ul>
    </section>
    <section class="tickets-list">
        <h2 class="visually-hidden">Самые новые предложения</h2>
        <div class="tickets-list__wrapper">
            <div class="tickets-list__header">
                <p class="tickets-list__title">Самое свежее</p>
            </div>
            <ul>
                <li class="tickets-list__item">
                    <div class="ticket-card ticket-card--color08">
                        <div class="ticket-card__img">
                            <img src="img/item08.jpg" srcset="img/item08@2x.jpg 2x" alt="Изображение товара">
                        </div>
                        <div class="ticket-card__info">
                            <span class="ticket-card__label">Куплю</span>
                            <div class="ticket-card__categories">
                                <a href="#">ЭЛЕКТРОНИКА</a>
                            </div>
                            <div class="ticket-card__header">
                                <h3 class="ticket-card__title"><a href="#">Фотик Canon</a></h3>
                                <p class="ticket-card__price"><span class="js-sum">32 000</span> ₽</p>
                            </div>
                            <div class="ticket-card__desc">
                                <p>Куплю вот такую итальянскую кофеварку, можно любой фирмы...</p>
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
    </section>
    <section class="tickets-list">
        <h2 class="visually-hidden">Самые обсуждаемые предложения</h2>
        <div class="tickets-list__wrapper">
            <div class="tickets-list__header">
                <p class="tickets-list__title">Самые обсуждаемые</p>
            </div>
            <ul>
                <li class="tickets-list__item">
                    <div class="ticket-card ticket-card--color10">
                        <div class="ticket-card__img">
                            <img src="img/item10.jpg" srcset="img/item10@2x.jpg 2x" alt="Изображение товара">
                        </div>
                        <div class="ticket-card__info">
                            <span class="ticket-card__label">ПРОДАМ</span>
                            <div class="ticket-card__categories">
                                <a href="#">Дом</a>
                            </div>
                            <div class="ticket-card__header">
                                <h3 class="ticket-card__title"><a href="#">Мое старое кресло</a></h3>
                                <p class="ticket-card__price"><span class="js-sum">4000</span> ₽</p>
                            </div>
                            <div class="ticket-card__desc">
                                <p>Продам свое старое кресло, чтобы сидеть и читать книги зимними...</p>
                            </div>
                        </div>
                    </div>
                </li>

            </ul>
        </div>
    </section>
<?php endif; ?>
