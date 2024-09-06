<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\RunescapeItem */

$this->title = 'Create Runescape Item';
$this->params['breadcrumbs'][] = ['label' => 'Runescape Items', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="runescape-item-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <h3></h3>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
