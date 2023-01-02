<?php

/** @var yii\web\View $this */
/** @var Ticket $ticket */
/** @var CommentForm $model */

use app\models\Comment;
use app\models\forms\CommentForm;
use app\models\Ticket;
use delta\TicketHandler;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

    <section class="ticket">
        <div class="ticket__wrapper">
            <h1 class="visually-hidden">Карточка объявления</h1>
            <div class="ticket__content">
                <div class="ticket__img">
                    <img src="<?=Url::to('/uploads/tickets/'. $ticket->photo) ;?>" srcset="<?=Url::to('/uploads/tickets/'. $ticket->photo) ;?> 2x" alt="Изображение товара">
                </div>
                <div class="ticket__info">
                    <h2 class="ticket__title"><?=$ticket->header; ?></h2>
                    <div class="ticket__header">
                        <p class="ticket__price"><span class="js-sum"><?=$ticket->price; ?></span> ₽</p>
                        <p class="ticket__action"><?= TicketHandler::getLabel($ticket->type); ?></p>
                    </div>
                    <div class="ticket__desc">
                        <p><?=$ticket->text; ?></p>
                    </div>
                    <div class="ticket__data">
                        <p>
                            <b>Дата добавления:</b>
                            <span><?=date('d F Y', strtotime($ticket->date_add)); ?></span>
                        </p>
                        <p>
                            <b>Автор:</b>
                            <a href="#"><?=$ticket->user->name; ?></a>
                        </p>
                        <p>
                            <b>Контакты:</b>
                            <a href="mailto:shkatulkin@ya.ru"><?=$ticket->user->email; ?></a>
                        </p>
                    </div>
                    <ul class="ticket__tags">
                        <?php foreach ($ticket->categories as $category): ?>

                        <li>
                            <a href="<?=Url::to('/offers/category/' . $category->id) ;?>" class="category-tile category-tile--small">
                <span class="category-tile__image">
                  <img src="<?=Url::to('/uploads/tickets/'. $category->getRandomTitleImage()) ;?>" srcset = "<?=Url::to('/uploads/tickets/'. $category->getRandomTitleImage()) ;?> 2x " alt="Иконка категории">
                </span>
                                <span class="category-tile__label"><?=$category->name ;?></span>
                            </a>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <div class="ticket__comments">
                <h2 class="ticket__subtitle">Коментарии</h2>
                <?php if (Yii::$app->user->isGuest): ?>
                    <div class="ticket__warning">
                        <p>Отправка комментариев доступна <br>только для зарегистрированных пользователей.</p>
                        <a href="<?=URL::to('\login'); ?>" class="btn btn--big">Вход и регистрация</a>
                    </div>
                <?php else: ?>
                    <div class="ticket__comment-form">
                        <?php $form = ActiveForm::begin([
                            'method' =>'post',
                            'options' => ['class' => 'form comment-form'],
                            'errorCssClass' => 'form__field--invalid',
                            'fieldConfig' => [
                                'template' => '{input}{label}{error}',
                                'options' => ['class' => 'form__field'],
                                'errorOptions' => ['tag' => 'span']],
                        ]);
                        ?>
                        <div class="comment-form__header">
                            <a href="#" class="comment-form__avatar avatar">
                                <img src="<?=Url::to('/uploads/'. Yii::$app->user->identity->avatar) ?>" srcset="<?=Url::to('/uploads/'. Yii::$app->user->identity->avatar) ?>" alt="Аватар пользователя">
                            </a>
                            <p class="comment-form__author">Вам слово</p>
                        </div>
                        <div class="comment-form__field">
                            <?php
                            echo $form->field($model, 'comment')->textarea(['class' => 'js-field']);
                           ?>
                        </div>
                        <?=Html::submitButton('Отправить', ['class' => 'comment-form__button btn btn--white js-button']) ?>
                        <?php ActiveForm::end(); ?>
                    </div>
                <?php endif; ?>

                <div class="ticket__comments-list">
                    <ul class="comments-list">
                        <?php foreach ($ticket->comments as $comment): ?>
                        <li>
                            <div class="comment-card">
                                <div class="comment-card__header">
                                    <a href="#" class="comment-card__avatar avatar">
                                        <img src="<?=Url::to('/uploads/'. $comment->user->avatar) ;?>" srcset="<?=Url::to('/uploads/'. $comment->user->avatar) ;?> 2x" alt="Аватар пользователя">
                                    </a>
                                    <p class="comment-card__author"><?=$comment->user->name; ?></p>
                                </div>
                                <div class="comment-card__content">
                                    <p><?=$comment->text; ?></p>
                                </div>
                            </div>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <?php if(Comment::find()->where(['ticket_id' => $ticket->id])->count() === 0): ;?>
                <div class="ticket__message">
                    <p>У этой публикации еще нет ни одного комментария.</p>
                </div>
                <?php endif; ?>
            </div>
            <button class="chat-button" type="button" aria-label="Открыть окно чата"></button>
        </div>
    </section>






