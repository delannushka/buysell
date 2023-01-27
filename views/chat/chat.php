<?php
/** @var yii\web\View $this */
/** @var ChatForm $model */
/** @var $messages */

use app\assets\FirebaseAsset;
use yii\widgets\ActiveForm;
use app\models\forms\ChatForm;
FirebaseAsset::register($this);

?>
<section class="chat <!--visually-hidden-->">
    <h2 class="chat__subtitle">Чат с продавцом</h2>
    <ul class="chat__conversation">
        <?php if ($messages !== null): ?>
            <?php foreach ($messages as $message): ?>
                <li class="chat__message">
                    <div class="chat__message-title">
                        <span class="chat__message-author"><?=($message['user_id'] !== $sellerId ) ? 'Вы' : 'Продавец'; ?></span>
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
        'options' => ['class' => 'chat__form'],

    ]); ?>
        <label class="visually-hidden" for="chat-field">Ваше сообщение в чат</label>
    <?= $formChat->field($model, 'message')->textarea(['options' => ['class' => 'chat__form-message']]) ?>
    <button class="chat__form-button" type="submit" aria-label="Отправить сообщение в чат"></button>
    <?php ActiveForm::end(); ?>
</section>