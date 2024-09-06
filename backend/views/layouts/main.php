<?php

/* @var $this \yii\web\View */
/* @var $content string */

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\bootstrap4\Breadcrumbs;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script defer src="https://use.fontawesome.com/releases/v5.3.1/js/all.js" crossorigin="anonymous"></script>
</head>
<body>
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => Yii::$app->name,
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'navbar navbar-expand-lg fixed-top navbar-dark bg-primary',
        ],
    ]);
    $menuItems = [
        ['label' => 'Home', 'url' => ['/']],
    ];

    if (Yii::$app->user->can('readRunescapeUser')) {
        $menuItems[] = ['label' => 'Roster', 'url' => ['/roster/index']];
    }

    if (Yii::$app->user->can('readCappingRaffle')) {
        $menuItems[] = ['label' => 'Capping Raffles', 'url' => ['/capping-raffle/index']];
    }

    if (Yii::$app->user->can('readRunescapeItem')) {
        $menuItems[] = ['label' => 'Items', 'url' => ['/item/index']];
    }

    if (Yii::$app->user->can('siteadmin')) {
        $menuItems[] = ['label' => 'Events', 'url' => ['/event/index']];
        $menuItems[] = ['label' => 'Ranks', 'url' => ['/rank/index']];
        $menuItems[] = ['label' => 'Audit', 'url' => ['/audit']];
    }

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => 'Login', 'url' => ['/site/login']];
    } else {
        $menuItems[] = ['label' => 'Logout (' . Yii::$app->user->identity->username . ')', 'url' => ['/site/logout']];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ml-auto'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
