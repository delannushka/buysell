<?php

/** @var yii\web\View $this */
/** @var Ticket $ticket */
/** @var CommentForm $model */

use app\models\Comment;
use app\models\forms\CommentForm;
use app\models\Ticket;
use app\widgets\CommentWidget;
use app\widgets\TicketViewWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Объявление';
?>

<section class="ticket">
    <div class="ticket__wrapper">
        <h1 class="visually-hidden">Карточка объявления</h1>
        <div class="ticket__content">
            <?=TicketViewWidget::widget(['ticket' => $ticket]); ?>
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
                            'errorOptions' => ['tag' => 'span']
                        ]
                    ]); ?>
                    <div class="comment-form__header">
                        <a href="#" class="comment-form__avatar avatar">
                            <img src="<?=Url::to('/uploads/avatar/'. Yii::$app->user->identity->avatar) ?>" srcset="<?=Url::to('/uploads/avatar/'. Yii::$app->user->identity->avatar) ?>" alt="Аватар пользователя">
                        </a>
                        <p class="comment-form__author">Вам слово</p>
                    </div>
                    <div class="comment-form__field">
                        <?php $model->comment = '';
                        echo $form->field($model, 'comment')->textarea(['class' => 'js-field']); ?>
                    </div>
                    <?=Html::submitButton('Отправить', ['class' => 'comment-form__button btn btn--white js-button']) ?>
                    <?php ActiveForm::end(); ?>
                </div>
            <?php endif; ?>

            <div class="ticket__comments-list">
                <ul class="comments-list">
                    <?php foreach ($ticket->comments as $comment) {
                        if ($comment->status === 1) {
                            echo CommentWidget::widget(['comment' => $comment]);
                        }
                    } ?>
                </ul>
            </div>
            <?php if(Comment::find()->where(['ticket_id' => $ticket->id, 'status' => 1])->count() === 0): ;?>
                <div class="ticket__message">
                    <p>У этой публикации еще нет ни одного комментария.</p>
                </div>
            <?php endif; ?>
        </div>
        <button class="chat-button" type="button" aria-label="Открыть окно чата"></button>
    </div>
</section>








