<?php

use kartik\date\DatePicker;
use kartik\select2\Select2;
use kartik\switchinput\SwitchInput;
use kartik\touchspin\TouchSpin;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RunescapeUser */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="runescape-user-form w-50">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'on_break')->widget(DatePicker::class, [
        'options' => [
            'placeholder' => 'Enter break start date...',
            'type' => DatePicker::TYPE_COMPONENT_APPEND,
        ],
        'pluginOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd',
        ]
    ]); ?>

    <div class="row">
        <div class="col">
            <?= $form->field($model, 'capped')->widget(SwitchInput::class) ?>
        </div>
        <div class="col">
            <?= $form->field($model, 'visited')->widget(SwitchInput::class) ?>
        </div>
    </div>

    <?php if ($model->canGetPoints() && !$model->private_profile): ?>
        <?= $form->field($model, 'rank_points')->widget(TouchSpin::class, [
            'pluginOptions' => [
                'min' => null,
                'max' => 64,
                'verticalbuttons' => true,
            ]
        ]); ?>
    <?php endif; ?>

    <div class="row">
        <div class="col">
            <?= $form->field($model, 'in_clan')->widget(SwitchInput::class) ?>
        </div>
        <div class="col">
            <?= $form->field($model, 'old_name')->widget(SwitchInput::class) ?>
        </div>
    </div>

    <?= $form->field($model, 'old_user_id')->widget(Select2::class, [
        'data' => $old_names,
        'options' => ['placeholder' => 'Select an old username'],
        'pluginOptions' => [
        'allowClear' => true
        ],
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
