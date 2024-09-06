<?php

use Carbon\Carbon;
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\date\DatePicker;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CappingRaffleSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Capping Raffles';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="capping-raffle-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            [
                'attribute' => 'reset_at',
                'value' => function ($model) {
                    return Carbon::parse($model->reset_at)->format('m/d/yy');
                },
                'filter' => DatePicker::widget([
                    'name' => 'CappingRaffleSearch[reset_at]',
                    'type' => DatePicker::TYPE_INPUT,
                    'value' => $searchModel->reset_at,
                    'pluginOptions' => [
                        'autoclose' => true,
                        'format' => 'mm/dd/yyyy'
                    ]
                ]),
            ],
            [
                'attribute' => 'winner',
                'label' => 'Winner',
                'value' => 'winner.username',
            ],
            [
                'class' => '\kartik\grid\BooleanColumn',
                'attribute' => 'paid',
                'vAlign' => 'middle',
                'trueLabel' => 'Paid',
                'falseLabel' => 'Unpaid',
            ],
            [
                'class' => 'kartik\grid\ActionColumn',
                'template'=>'{view}',
            ],
        ],
    ]); ?>


</div>
