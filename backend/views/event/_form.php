<?php

use kartik\datetime\DateTimePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RunescapeEvent */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="runescape-event-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col">
            <?= $form->field($model, 'start_date')->label('Start Date/Time (Game Time)')->widget(DateTimePicker::class, [
                'pluginOptions' => [
                    'autoclose' => true,
                    'showMeridian' => true,
                ]
            ]) ?>
        </div>
        <div class="col">
            <?= $form->field($model, 'end_date')->label('End Date/Time (Game Time)')->widget(DateTimePicker::class, [
                'pluginOptions' => [
                    'autoclose' => true,
                    'showMeridian' => true,
                ]
            ]) ?>
        </div>
    </div>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'host_id')->label('Host')->widget(Select2::class, [
        'data' => $users,
        'options' => ['placeholder' => 'Select a User'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'cohost_id')->label('Cohost (Optional)')->widget(Select2::class, [
        'data' => $users,
        'options' => ['placeholder' => 'Select a User'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
