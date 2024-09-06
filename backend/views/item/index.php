<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RunescapeItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Runescape Items';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="runescape-item-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= Html::a('Manually Add Item', ['create'], ['class' => 'btn btn-success']) ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'name',
            [
                'label' => 'Image',
                'format' => 'raw',
                'value' => function($model) {
                    if (!empty($model->fullUrl)) {
                        return '<img class="img-fluid" src="' . $model->fullUrl . '">';
                    } else {
                        return '<i class="fa fa-image"></i>';
                    }
                }
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template'=>'{view}{update}',
            ],
        ],
    ]); ?>


</div>
