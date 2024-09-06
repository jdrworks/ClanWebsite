<?php

use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\models\RunescapeUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Clan Roster';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="runescape-user-index">

    <div class="row">
        <div class="col">
            <h1>Clan Roster</h1>
        </div>
        <div class="col text-right">
            <?= html::a('Rank Report', ['/roster/report'], ['class' => 'btn btn-primary']); ?>
        </div>
    </div>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'username',
            [
                'class' => '\kartik\grid\EnumColumn',
                'attribute' => 'rank_id',
                'label' => 'Rank',
                'enum' => $rankEnum,
                'format' => 'raw',
                'value' => function($model) {
                    $rank = \common\models\RunescapeRank::findOne($model->rank_id);
                    return '<img src="/images/ranks/' . $rank->name . '.png"></img> ' . $rank->name;
                },
            ],
            [
                'class' => '\kartik\grid\BooleanColumn',
                'attribute' => 'capped',
                'vAlign' => 'middle',
                'trueLabel' => 'Capped',
                'falseLabel' => 'Not Capped',
            ],
            [
                'class' => '\kartik\grid\BooleanColumn',
                'attribute' => 'visited',
                'vAlign' => 'middle',
                'trueLabel' => 'Visited',
                'falseLabel' => 'Not Visited',
            ],
            [
                'class' => '\kartik\grid\BooleanColumn',
                'attribute' => 'private_profile',
                'label' => 'Profile',
                'vAlign' => 'middle',
                'trueLabel' => 'Private',
                'trueIcon' => '<i class="fa fa-eye-slash text-danger"></i>',
                'falseLabel' => 'Public',
                'falseIcon' => '<i class="fa fa-eye text-success"></i>',
            ],
            [
                'class' => '\kartik\grid\BooleanColumn',
                'attribute' => 'in_clan',
                'vAlign' => 'middle',
                'trueLabel' => 'In Clan',
                'falseLabel' => 'Not In Clan',
            ],
            [
                'class' => '\kartik\grid\BooleanColumn',
                'attribute' => 'old_name',
                'vAlign' => 'middle',
                'trueLabel' => 'Old Username',
                'falseLabel' => 'Current Username',
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template'=>'{view} {update}',
            ],
        ],
    ]); ?>


</div>
