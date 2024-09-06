<?php

use Carbon\Carbon;
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\CappingRaffle */

$this->title = Carbon::parse($model->reset_at)->format('m/d/yy');
$this->params['breadcrumbs'][] = ['label' => 'Capping Raffles', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="capping-raffle-view">

    <div class="row">
        <div class="col">
            <h1><?= Html::encode($this->title) ?></h1>
            <h4>
                Winner: <?= $model->winner->username ?>
                -
                <?= $model->paid ? '<span class="badge badge-success">Paid</span>' : '<span class="badge badge-danger">Unpaid</span>'; ?>
            </h4>
        </div>
        <div class="col text-right">
            <?php if (!$model->paid): ?>
                <?= Html::a('Re-Roll Winner', ['/capping-raffle/reset', 'id' => $model->id], ['class' => 'btn btn-warning']); ?>
                <?= Html::a('Mark Paid', ['/capping-raffle/paid', 'id' => $model->id], ['class' => 'btn btn-success']); ?>
            <?php endif; ?>
        </div>
    </div>

    <hr>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
                'username'
        ],
    ]) ?>

</div>
