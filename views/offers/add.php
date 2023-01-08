<?php

/** @var yii\web\View $this */
/** @var TicketForm $model
 */

use app\models\Category;
use app\models\forms\TicketForm;
use delta\TicketHandler;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

?>

<main class="page-content">
    <section class="ticket-form">
        <div class="ticket-form__wrapper">
            <h1 class="ticket-form__title">Новая публикация</h1>
            <div class="ticket-form__tile">

                <?php $form = ActiveForm::begin([
                    'id' => 'ticket-form',
                    'method' => 'post',
                    'options' => ['class' => 'ticket-form__form form'],
                    'errorCssClass' => 'form__field--invalid span',
                    'fieldConfig' => [
                        'template' => '<div class="form__field">{input}{label}<span>{error}</span></div>',
                        'inputOptions' => ['class' => 'js-field'],
                        'errorOptions' => ['tag' => 'span'],
                    ],
                ]); ?>

                <div class="ticket-form__avatar-container js-preview-container">
                    <div class="ticket-form__avatar js-preview"></div>
                    <div class="ticket-form__field-avatar">
                        <?=$form->field($model, 'avatar', ['template' => "{input}\n{error}"])
                            ->fileInput([
                                'class' => 'visually-hidden js-file-field',
                                'id' => 'avatar'
                            ])
                        ?>
                        <label for="avatar">
                            <span class="ticket-form__text-upload">Загрузить фото…</span>
                            <span class="ticket-form__text-another">Загрузить другое фото…</span>
                        </label>
                    </div>
                </div>

                    <div class="ticket-form__content">
                        <?=$form->field($model, 'header')->input('text'); ?>
                        <div class="ticket-form__row">
                            <?= $form->field($model, 'text', [
                                'options' => [
                                    'class' => 'form__field',
                                    'cols' => 30,
                                    'rows' => 10
                                ]])->textarea() ?>
                        </div>
                            <?= $form->field($model, 'categories', [
                                'options' => ['class' => 'ticket-form__row'],
                                'template' => "{input}"
                            ])->dropDownList(
                                ArrayHelper::map(Category::find()->all(), 'id', 'name'),
                                    [
                                        'class' => 'ticket-form__row form__select js-multiple-select',
                                        'placeholder' => "Выбрать категорию публикации",
                                        'multiple' => true
                                    ]
                                )
                            ?>
                        <div class="ticket-form__row">
                            <?=$form->field($model, 'price', ['options' => ['class' => 'form__field form__field--price']])->input('number', ['class' => 'js-field js-price']); ?>


                            <?=$form->field($model, 'type', ['template' => '{input}{error}'])
                                ->radioList(TicketHandler::getTypeMap(), ['class' => 'form__switch switch',
                                    'item' => function ($index, $label, $name, $checked, $value) {
                                    return
                                        Html::beginTag('div', ['class' => 'switch__item']) .
                                        Html::radio($name, $checked, ['value' => $value, 'id' => $index, 'class' => 'visually-hidden']) .
                                        Html::label($label, $index, ['class' => 'switch__button']) .
                                        Html::endTag('div');
                                    },
                                ]);
                            ?>
                        </div>
                    </div>
                    <?=Html::submitButton('Опубликовать', ['class' => 'form__button btn btn--medium js-button']) ?>
                    <?php ActiveForm::end(); ?>
            </div>
        </div>
    </section>
</main>
