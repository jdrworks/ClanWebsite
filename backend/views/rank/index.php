<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RunescapeRankSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Runescape Ranks';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="runescape-rank-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            'name',
            'id',
            'rank_points',
            'promotion_points',
        ],
    ]); ?>


</div>
