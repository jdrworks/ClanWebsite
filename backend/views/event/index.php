<?php

use Carbon\Carbon;
use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\datetime\DateTimePicker;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\RunescapeEventSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Events';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="runescape-event-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Event', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'kartik\grid\SerialColumn'],

            'name',
            'description:ntext',
            [
                'attribute' => 'host',
                'label' => 'Host',
                'value' => 'host.username',
            ],
            [
                'attribute' => 'cohost',
                'label' => 'Cohost',
                'value' => 'cohost.username',
            ],
            [
                'attribute' => 'start_date',
                'value' => function ($model) {
                    return Carbon::parse($model->start_date)->format('H:i m/d/yy');
                },
                'filter' => DateTimePicker::widget([
                   'name' => 'RunescapeEventSearch[start_date]',
                   'type' => DateTimePicker::TYPE_INPUT,
                   'value' => $searchModel->start_date,
                   'pluginOptions' => [
                       'autoclose' => true,
                       'format' => 'hh:ii P mm/dd/yyyy'
                   ]
               ]),
            ],
            [
                'attribute' => 'start_date',
                'value' => function ($model) {
                    return Carbon::parse($model->end_date)->format('H:i m/d/yy');
                },
                'filter' => DateTimePicker::widget([
                   'name' => 'RunescapeEventSearch[end_date]',
                   'type' => DateTimePicker::TYPE_INPUT,
                   'value' => $searchModel->end_date,
                   'pluginOptions' => [
                       'autoclose' => true,
                       'format' => 'hh:ii P mm/dd/yyyy'
                   ]
               ]),
            ],

            [
                'class' => 'kartik\grid\ActionColumn',
            ],
        ],
    ]); ?>


</div>
