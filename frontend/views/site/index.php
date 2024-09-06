<?php

/* @var $this yii\web\View */
/* @var $reset Carbon\Carbon */
/* @var $currentEvents array<common\models\RunescapeEvent> */
/* @var $upcomingEvents array<common\models\RunescapeEvent> */

use Carbon\Carbon;

$this->title = Yii::$app->name;
?>

<?php
    $this->registerJs(
        "moment.tz.add('Etc/UTC|UTC|0|0||');
            var reset = moment.utc('{$reset->toDateTimeString()}');
            
            setInterval(function() {
                var now = moment().utc();
                var diffTime = reset.diff(now);
                var duration = moment.duration(diffTime);
                var days = duration.days();
                days = days.toString().length < 2 ? '0' + days : days;
                var hours = duration.hours();
                hours = hours.toString().length < 2 ? '0' + hours : hours;
                var minutes = duration.minutes();
                minutes = minutes.toString().length < 2 ? '0' + minutes : minutes;
                var seconds = duration.seconds();
                seconds = seconds.toString().length < 2 ? '0' + seconds : seconds;
                
                $('#citadel-timer').text(days + ':' + hours + ':' + minutes + ':' + seconds);
                
                if (days < 1 && hours > 12) {
                    $('#citadel-timer').removeClass('text-danger').addClass('text-warning');
                } else if (days < 1 && hours < 12) {
                    $('#citadel-timer').removeClass('text-warning').addClass('text-danger');
                } else {
                    $('#citadel-timer').removeClass('text-warning text-danger');
                }
            }, 1000);"
    );
?>

<div class="container">
    <div class="row">
        <div class="col">
            <h2>
                Current Events
            </h2>

            <?php if (count($currentEvents) === 0): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">No Current Events</h5>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($currentEvents as $event): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">
                                <?= $event->name ?>
                                <small class="float-right">
                                    Starts:
                                    <?= Carbon::parse($event->start_date, 'UTC')->format('H:i m/d/y'); ?>
                                    (Game Time)
                                </small>
                            </h5>
                            <p class="card-text">
                                <strong>Host:</strong> <?= $event->host->username ?>
                                <?php if (!empty($event->cohost)): ?>
                                    <strong>Cohost:</strong> <?= $event->cohost->username ?>
                                <?php endif; ?>
                            </p>
                            <p class="card-text"><?= $event->description ?></p>
                            <span class="float-right">
                                Ends:
                                <?= Carbon::parse($event->end_date, 'UTC')->format('H:i m/d/y'); ?>
                                (Game Time)
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>


            <h2>
                Upcoming Events
            </h2>

            <?php if (count($upcomingEvents) === 0): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">No Upcoming Events</h5>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($upcomingEvents as $event): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">
                                <?= $event->name ?>
                                <small class="float-right">
                                    Starts:
                                    <?= Carbon::parse($event->start_date, 'UTC')->format('H:i m/d/y'); ?>
                                    (Game Time)
                                </small>
                            </h5>
                            <p class="card-text">
                                <strong>Host:</strong> <?= $event->host->username ?>
                                <?php if (!empty($event->cohost)): ?>
                                    <strong>Cohost:</strong> <?= $event->cohost->username ?>
                                <?php endif; ?>
                            </p>
                            <p class="card-text"><?= $event->description ?></p>
                            <span class="float-right">
                                Ends:
                                <?= Carbon::parse($event->end_date, 'UTC')->format('H:i m/d/y'); ?>
                                (Game Time)
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <div class="col-12 col-md-4 col-lg-3">
            <a href="https://discord.gg/WXegyxHzWc" target="_blank" class="btn btn-primary btn-lg btn-block mb-3">
                <i class="fab fa-discord"></i> Discord Server
            </a>
            <div class="card">
                <h3 class="card-header">
                    Clan Citadel
                </h3>
                <div class="card-body text-center">
                    <h4>Next Reset:</h4>
                    <h5 id="citadel-timer">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </h5>
                    <h4>Raffle Winner:</h4>
                    <h5><?= $winner ?></h5>
                </div>
            </div>
        </div>
    </div>
</div>