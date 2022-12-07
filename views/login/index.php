<?php

/** @var yii\web\View $this */
/** @var LoginForm $model */


use app\models\forms\LoginForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<section class="login">
    <h1 class="visually-hidden">Логин</h1>
    <?php $form = ActiveForm::begin([
        //'action' => ['some-controller/some-action','id' => '101'],
        'id' => 'login-form',
        'method' => 'post',
        'options' => ['class' => 'login__form form'],
        'errorCssClass' => 'form__field--invalid span',
        'fieldConfig' => [
            'template' => '<div class="form__field login__field">{input}{label}<span>{error}</span></div>',
            'inputOptions' => ['class' => 'js-field'],
            'errorOptions' => ['tag' => 'span'],
        ],
    ]); ?>

        <div class="login__title">
            <a class="login__link" href="<?= Url::to(['/register']) ?>">Регистрация</a>
            <h2>Вход</h2>
        </div>

        <?=$form->field($model, 'email')->input('email'); ?>
        <?=$form->field($model, 'password')->passwordInput(); ?>

        <?=Html::submitButton('Войти', ['class' => 'login__button btn btn--medium js-button']) ?>

    <a class="btn btn--small btn--flex btn--white" href="#">
        Войти через <span class="icon icon--vk"></span>
    </a>
    <?php ActiveForm::end(); ?>
</section>
