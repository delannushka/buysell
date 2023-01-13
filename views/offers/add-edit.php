<?php

/** @var yii\web\View $this */
/** @var Ticket $ticket */
/** @var TicketForm $model */

use app\models\Category;
use app\models\Ticket;
use app\models\forms\TicketForm;
use delta\TicketHandler;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Редактирование объявления';
?>

<section class="ticket-form">
    <div class="ticket-form__wrapper">
        <h1 class="ticket-form__title"><?=(Yii::$app->request->url !== '/offers/add')  ? 'Редактировать публикацию' : 'Новая публикация'; ?></h1>
        <div class="ticket-form__tile">
            <?php $form = ActiveForm::begin([
                'options' => ['class' => 'ticket-form__form form'],
                'errorCssClass' => 'form__field--invalid span',
                'fieldConfig' => ['errorOptions' => ['tag' => 'span']]
            ]); ?>

                <div class="ticket-form__avatar-container js-preview-container <?=($model->avatar) ? 'uploaded' : '' ?>">
                    <div class="ticket-form__avatar js-preview">
                        <?php if (Yii::$app->request->url !== '/offers/add'): ?>
                            <img src="<?=URL::to('/uploads/tickets/' . $model->avatar); ?>" srcset="<?=URL::to('/uploads/tickets/' . $model->avatar); ?> 2x" alt="Изображение объявления">
                        <?php endif; ?>
                    </div>
                    <div class="ticket-form__field-avatar">
                        <?=$form->field($model, 'avatar', ['template' => "{input}\n{error}"])
                            ->fileInput(['class' => 'visually-hidden js-file-field', 'id' => 'avatar']) ?>
                        <label for="avatar">
                            <span class="ticket-form__text-upload">Загрузить фото…</span>
                            <span class="ticket-form__text-another">Загрузить другое фото…</span>
                        </label>
                    </div>
                </div>

                <div class="ticket-form__content">
                    <div class="ticket-form__row">
                        <div class="form__field">
                            <?=$form->field($model, 'header')->textInput(['class' => 'js-field']); ?>
                        </div>
                    </div>
                    <div class="ticket-form__row">
                        <div class="form__field">
                            <?=$form->field($model, 'text')->textarea(['cols' => 30,
                                    'rows' => 10,'class' => 'js-field']); ?>
                        </div>
                    </div>
                    <div class="ticket-form__row">
                        <div class="form__field">
                            <?=$form->field($model, 'categories', ['options' => ['tag' => false]])
                                ->dropDownList(ArrayHelper::map(Category::find()->all(), 'id', 'name'), [
                                    'class' => 'form__select js-multiple-select',
                                    'placeholder' => "Выбрать категорию публикации",
                                    'multiple' => true])
                                ->label(false)
                            ?>
                        </div>
                    </div>
                    <div class="ticket-form__row">
                        <div class ="form__field">
                            <?= $form->field($model, 'price', ['options' => [
                                    'class' => 'form__field--price'],
                                    'template' => "{input}{label}{error}"])
                                    ->input('number', ['class' => 'js-field js-price']);
                            ?>
                        </div>
                        <div class="form__switch switch">
                            <?=$form->field($model, 'type', ['options' => [
                                'class' => 'form__field--price'],
                                'template' => "{input}{error}"])
                                ->radioList(TicketHandler::getTypeMap(), [
                                        'class' => 'form__switch switch',
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
                </div>
                <?=Html::submitButton((Yii::$app->request->url !== '/offers/add')  ? 'Сохранить' : 'Опубликовать', ['class' => 'form__button btn btn--medium js-button']); ?>
            <?php ActiveForm::end() ?>
        </div>
    </div>
</section>