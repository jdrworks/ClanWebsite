<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\CappingRaffle */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="capping-raffle-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'reset_at')->textInput() ?>

    <?= $form->field($model, 'winner_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
