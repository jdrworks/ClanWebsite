<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RunescapeItem */

$this->title = 'Update Runescape Item: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Runescape Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="runescape-item-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'imageAnchors' => $imageAnchors,
    ]) ?>

</div>
