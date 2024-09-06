<?php

/* @var $this yii\web\View */
/* @var $latestRaffle common\models\CappingRaffle */

$this->title = 'Fkn Amazing Admin Panel';
?>

<div class="container">
    <div class="row">
        <div class="col">
            <?php if (!empty($latestRaffle)): ?>
                <div class="card">
                    <h3 class="card-header">
                        Capping Raffle
                    </h3>
                    <div class="card-body text-center">
                        <h4>Winner:</h4>
                        <h5 class="<?= $latestRaffle->paid ? 'text-success' : 'text-danger' ?>">
                            <?= $latestRaffle->paid ? 'Paid' : 'Not Paid' ?>
                        </h5>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>