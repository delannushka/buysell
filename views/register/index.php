<?php

/** @var yii\web\View $this */
/** @var RegisterForm $model
 */


use app\models\forms\RegisterForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>
<section class="sign-up">
    <h1 class="visually-hidden">Регистрация</h1>
        <?php $form = ActiveForm::begin([
            //'action' => ['some-controller/some-action','id' => '101'],
            'id' => 'register-form',
            'method' => 'post',
            'options' => ['class' => 'sign-up__form form'],
            'errorCssClass' => 'form__field--invalid span',
            'fieldConfig' => [
                'template' => '<div class="form__field sign-up__field">{input}{label}<span>{error}</span></div>',
                'inputOptions' => ['class' => 'js-field'],
                'errorOptions' => ['tag' => 'span'],
            ],
        ]); ?>
        <div class="sign-up__title">
            <h2>Регистрация</h2>
            <a class="sign-up__link" href="<?= Url::to(['/login']) ?>">Вход</a>
        </div>

        <div class="sign-up__avatar-container js-preview-container">
            <div class="sign-up__avatar js-preview"></div>
            <div class="sign-up__field-avatar">
                <?=$form->field($model, 'avatar', ['template' => "{input}\n{error}"])
                    ->fileInput([
                        'class' => 'visually-hidden js-file-field',
                        'id' => 'avatar'
                    ])
                ?>
                <label for="avatar">
                    <span class="sign-up__text-upload">Загрузить аватар…</span>
                    <span class="sign-up__text-another">Загрузить другой аватар…</span>
                </label>
            </div>
        </div>



        <?=$form->field($model, 'name')->input('text'); ?>
        <?=$form->field($model, 'email')->input('email'); ?>
        <?=$form->field($model, 'password')->passwordInput(); ?>
        <?=$form->field($model, 'repeatPassword')->passwordInput(); ?>

        <?=Html::submitButton('Создать аккаунт', ['class' => 'sign-up__button btn btn--medium js-button']) ?>

        <a class="btn btn--small btn--flex btn--white" href="#">
            Войти через
            <span class="icon icon--vk"></span>
        </a>
        <?php ActiveForm::end(); ?>
</section>
