<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\RunescapeItem */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="runescape-item-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>


    <?php if (isset($model->name)): ?>
        <div class="form-group">
            <div class="list-group">
                <?php foreach ($imageAnchors as $index => $imageAnchor): ?>
                    <label class="list-group-item" for="<?= $index; ?>">
                        <img src="<?= $imageAnchor; ?>">
                        <input type="radio" name="itemImage" id="<?= $index; ?>" value="<?= $imageAnchor?>">
                    </label>
                <?php endforeach; ?>
            </div>
            <label>Manual Image Url</label>
            <input type="text" class="form-control" name="manualItemImage">
        </div>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
