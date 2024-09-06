<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RunescapeRank */

$this->title = 'Update Runescape Rank: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Runescape Ranks', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="runescape-rank-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
