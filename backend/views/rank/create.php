<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RunescapeRank */

$this->title = 'Create Runescape Rank';
$this->params['breadcrumbs'][] = ['label' => 'Runescape Ranks', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="runescape-rank-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
