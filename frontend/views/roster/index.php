<?php

use yii\bootstrap4\Modal;
use yii\helpers\Html;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel frontend\models\RunescapeUserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Clan Roster';
?>
<div class="runescape-user-index">

    <h1>Clan Roster</h1>

    <?php Modal::begin([
        'title' => '<h2>Public Profile Instructions</h2>',
        'toggleButton' => false,
        'size' => Modal::SIZE_LARGE,
        'options' => [
            'id' => 'profilePrivateInfo',
        ],
    ]); ?>

    <p>If you're Rune MetricsProfile is set to private, we cannot access your event log and check drops, capping, etc.</p>
    <p>To make your profile public, you must log in to the Runescape website and go to your account:</p>
    <img class="img-fluid mb-3" src="/images/profile_instructions_1.jpg">
    <p>Then make sure you set your RuneMetrics Profile to Public:</p>
    <img class="img-fluid mb-3" src="/images/profile_instructions_2.jpg">
    <p>Lastly, make sure your event log settings are correctly set in-game. Make sure you have drops set correctly for
    events, and "Miscellaneous" for capping:</p>
    <img class="img-fluid" src="/images/profile_instructions_3.jpg">

    <?php Modal::end(); ?>

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
                'class' => 'kartik\grid\ActionColumn',
                'template'=>'{view}',
            ],
        ],
    ]); ?>


</div>
