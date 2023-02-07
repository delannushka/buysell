<?php

/** @var yii\web\View $this */
/** @var Ticket $ticket */
/** @var CommentForm $model */
/** @var ChatForm $modelChat */
/** @var $messages */
/** @var $sellerId */
/** @var $buyerId */

use app\models\Comment;
use app\models\forms\CommentForm;
use app\models\Ticket;
use app\widgets\CommentWidget;
use app\widgets\TicketViewWidget;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Pjax;
use app\assets\FirebaseAsset;
use app\models\User;
use app\models\forms\ChatForm;

FirebaseAsset::register($this);

if ($buyerId === null) {
    $secondPerson = '';
} else if (Yii::$app->user->id === $sellerId) {
    $secondPerson = User::findOne($buyerId)->name;
} else {
    $secondPerson = User::findOne($sellerId)->name;
}

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
                        <?=Html::submitButton('Отправить', [
                            'class' => 'comment-form__button btn btn--white js-button',
                            'name' => 'submit_comment',
                            'value' => 'comment'
                        ]); ?>
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
            <?php if(Comment::find()->where(['ticket_id' => $ticket->id, 'status' => 1])->count() === 0): ?>
                <div class="ticket__message">
                    <p>У этой публикации еще нет ни одного комментария.</p>
                </div>
            <?php endif; ?>
        </div>
        <button class="chat-button" type="button" aria-label="Открыть окно чата"></button>
    </div>
</section>

<?php if (!Yii::$app->user->isGuest) : ?>
    <section class="chat visually-hidden">
        <?php Pjax::begin(); ?>
            <h2 class="chat__subtitle"><?=(Yii::$app->user->id !== $sellerId) ? 'Чат с продавцом' : 'Чат с покупателем' ?></h2>
            <ul class="chat__conversation">
                <?php if ($messages !== null): ?>
                    <?php foreach ($messages as $message): ?>
                        <li class="chat__message">
                            <div class="chat__message-title">
                                <span class="chat__message-author"><?=(Yii::$app->user->id === $message['user_id'] ) ? 'Вы' : $secondPerson ?></span>
                                <time class="chat__message-time"><?=Yii::$app->formatter->asDate($message['dt_add'], 'php:H:i'); ?></time>
                            </div>
                            <div class="chat__message-content">
                                <?=$message['message']; ?>
                            </div>
                        </li>
                    <?php endforeach;
                endif; ?>
            </ul>

            <?php $formChat = ActiveForm::begin([
                'id' => 'chat-form',
                'options' => [
                    'class' => 'chat__form',
                    'data-pjax' => true
                ],
            ]); ?>
                <?= $formChat->field($modelChat, 'message', ['options' => ['tag' => false], 'inputOptions' => ['class' => 'chat__form-message']])->textarea(['placeholder' => "Ваше сообщение в чат"])->label(false) ?>
                <?=Html::submitButton('Отправить', ['class' => 'chat__form-button',
                    'value'=>'chat', 'name'=>'submit_chat']) ?>
            <?php ActiveForm::end(); ?>
        <?php Pjax::end(); ?>
    </section>
<?php else: ?>
    <section class="chat visually-hidden">
        <h2 class="chat__subtitle">Для того, чтобы напистаь сообщение продавцу, зайдите на сайт</h2>
    </section>
<?php endif; ?>










