<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\RunescapeUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rank Report';
$this->params['breadcrumbs'][] = ['label' => 'Clan Roster', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="runescape-user-report">

    <h1>Clan Rank Report</h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'username',
            'last_active',
            [
                'class' => '\kartik\grid\EnumColumn',
                 'attribute' => 'rank_id',
                 'label' => 'Rank',
                 'enum' => $rankEnum,
                 'format' => 'raw',
                 'value' => function($model) {
                     return '<img src="/images/ranks/' . $model->rank->name . '.png"></img> ' . $model->rank->name;
                 },
            ],
            [
                'label' => 'Points for Current Rank',
                'attribute' => 'rank_rank_points',
                'value' => function($model) {
                    return $model->rank->rank_points;
                },
            ],
            [
                'label' => 'Current Rank Points',
                'attribute' => 'rank_points',
                'value' => function($model) {
                    return $model->rank_points;
                }
            ],
            [
                'label' => 'Points for Next Rank',
                'attribute' => 'rank_promotion_points',
                'value' => function($model) {
                    return $model->rank->promotion_points;
                },
            ],
            [
                'label' => 'Status',
                'format' => 'raw',
                'value' => function ($model) {
                    return $model->reportIcon;
                },
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template'=>'{view} {update}',
            ],
        ],
    ]); ?>

</div>
