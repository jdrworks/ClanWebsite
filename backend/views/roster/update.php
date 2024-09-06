<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RunescapeUser */

$this->title = 'Update Runescape User: ' . $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Clan Roster', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="runescape-user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'old_names' => $old_names,
    ]) ?>



</div>
