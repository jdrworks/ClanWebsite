<?php

use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\RunescapeUser */

$this->title = $model->username;
\yii\web\YiiAsset::register($this);

$js = <<< 'SCRIPT'
    $(function () {
        $('[data-toggle="popover"]').popover({
            'placement': 'top',
            'trigger': 'hover'
        });
    })
SCRIPT;

$this->registerJs($js);
?>
<div class="runescape-user-view">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <button class="btn btn-secondary">
            Rank:
            <span class="badge badge-light">
                    <?= $model->rank->name ?>
                </span>
        </button>
    </p>
    <p>
        <?php if ($model->private_profile): ?>
            <button class="btn btn-danger">
                Private Profile <i class="fa fa-eye-slash"></i>
            </button>
        <?php else: ?>
            <button class="btn btn-success">
                Public Profile <i class="fa fa-eye"></i>
            </button>
        <?php endif; ?>

        <?php if ($model->visited): ?>
            <button class="btn btn-success">
                Visited <i class="fa fa-check"></i>
            </button>
        <?php else: ?>
            <button class="btn btn-success">
                Visited <i class="fa fa-times"></i>
            </button>
        <?php  endif; ?>

        <?php if ($model->capped): ?>
            <button class="btn btn-success">
                Capped <i class="fa fa-check"></i>
            </button>
        <?php else: ?>
            <button class="btn btn-danger">
                Capped <i class="fa fa-times"></i>
            </button>
        <?php endif; ?>
    </p>

    <h2>Drop Logs</h2>
    <?= GridView::widget(
        [
            'dataProvider' => $dropLogs,
            'columns' => [
                [
                    'label' => 'Item',
                    'value' => function ($model) {
                        return $model->item->name;
                    }
                ],
                [
                    'attribute' => 'dropped_at',
                    'format' => ['date', 'php:m/d/Y h:i A'],
                ],
            ],

        ]
    ) ?>

</div>
