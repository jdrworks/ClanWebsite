<?php

use kartik\grid\GridView;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\RunescapeUser */

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => 'Clan Roster', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
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

    <?php if ($model->in_clan === 0 && $model->old_name === 0): ?>
        <h1>
            <button class="btn btn-danger" data-toggle="popover"  title="Left Clan" data-content="This person left the clan">
                <i class="fa fa-user-slash"></i>
            </button>
            <?= Html::encode($this->title) ?>
            <?= Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        </h1>
    <?php elseif ($model->old_name === 1): ?>
        <h1>
            <button class="btn btn-secondary" data-toggle="popover"  title="Old Username" data-content="This user changed their name.">
                <i class="fa fa-user-clock"></i>
            </button>
            <?= Html::encode($this->title) ?>
            <?= Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        </h1>
    <?php else: ?>
        <h1>
            <?= Html::encode($this->title) ?>
            <?= Html::a('<i class="fa fa-pencil-alt"></i>', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        </h1>
    <?php endif; ?>
    <p>
        <button class="btn btn-secondary">
            Rank:
            <span class="badge badge-light">
                <?= $model->rank->name ?>
            </span>
        </button>
        <?php if ($model->canGetPoints() && !$model->private_profile): ?>
            <button class="btn btn-secondary">
                Rank Points:
                <span class="badge badge-light">
                    <?= $model->rank_points ?>
                </span>
            </button>
        <?php endif; ?>
        <button class="btn btn-secondary">
            Last Active:
            <span class="badge badge-light">
                <?= $model->humanReadableDate($model->last_active) ?>
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
            <button class="btn btn-danger">
                Visited <i class="fa fa-times"></i>
            </button>
        <?php endif; ?>

        <?php if ($model->capped): ?>
            <button class="btn btn-success">
                Capped <i class="fa fa-check"></i>
            </button>
        <?php else: ?>
            <button class="btn btn-danger">
                Capped <i class="fa fa-times"></i>
            </button>
        <?php endif; ?>

        <?php if (!empty($model->on_break)): ?>
            <button class="btn btn-secondary">
                On Break Since:
                <span class="badge badge-light">
                    <?= $model->humanReadableDate($model->on_break, false) ?>
                </span>
            </button>
        <?php else: ?>
            <button class="btn btn-success">
                Not On Break
            </button>
        <?php endif; ?>
    </p>

    <h2>Old Usernames</h2>
    <?= GridView::widget([
         'dataProvider' => $oldUsernames,
         'columns' => [
             [
                 'label' => 'Old Usernames',
                 'attribute' => 'username',
             ]
         ],
     ]) ?>

    <h2>Capping Raffles</h2>
    <?= GridView::widget([
         'dataProvider' => $cappingRaffles,
         'columns' => [
             [
                 'attribute' => 'reset_at',
                 'format' => ['date', 'php:m/d/Y h:i A'],
             ],
         ],

     ]) ?>

    <h2>Drop Logs</h2>
    <?= GridView::widget([
         'dataProvider' => $dropLogs,
         'columns' => [
             [
                 'label' => 'Item',
                 'value' => function($model) {
                    return $model->item->name;
                 }
             ],
             [
                 'attribute' => 'dropped_at',
                 'format' => ['date', 'php:m/d/Y h:i A'],
            ],
         ],

     ]) ?>

    <h2>User Notes</h2>
    <div class="list-group">
        <?php if (count($notes) > 0): ?>
            <?php foreach ($notes as $note): ?>
                <div class="list-group-item">
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1"><?= $note->creator->username ?></h5>
                        <small><?= $model->humanReadableDate($note->created_at) ?></small>
                    </div>
                    <p class="mb-1"><?= $note->note ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="list-group-item">
                <p class="mb-1">No Notes Found</p>
            </div>
        <?php endif; ?>
    </div>

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($newNote, 'note')->textarea(['placeholder' => 'Add a Note'])->label('') ?>

        <div class="form-group">
            <?= Html::submitButton('Add Note', ['class' => 'btn btn-success']) ?>
        </div>

    <?php ActiveForm::end(); ?>

</div>
