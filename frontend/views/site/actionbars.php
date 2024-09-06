<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'sharf\'s Action Bars';
?>
<div class="site-actionbars">
    <h1><?= Html::encode($this->title) ?></h1>

    <h3>What are these?</h3>
    <p class="mb-5">
        These are my (sharf's) action bars that I use for different circumstances. I do not use revolution, only full
        manual and revolution++ (revo++, full auto) for afk bossing/slayer. People ask for what bars I use, and since I
        have more than I can save in the 13 in-game I decided to put them up here as a personal reference and also so I
        can direct others to them as well.
    </p>

    <h4>
        Full Manual Bars
        <small class="text-muted">Used for most bosses and some slayer</small>
    </h4>
    <ul class="list-group mb-5">
        <li class="list-group-item">
            <h5>Melee</h5>
            <img src="/images/action_bars/melee_manual.jpg">
        </li>
        <li class="list-group-item">
            <h5>Range</h5>
            <img src="/images/action_bars/range_manual.jpg">
        </li>
        <li class="list-group-item">
            <h5>Mage</h5>
            <img src="/images/action_bars/mage_manual.jpg">
        </li>
    </ul>
    <h4>
        Revolution++ Bars
        <small class="text-muted">Used for afk bosses and most slayer</small>
    </h4>
    <ul class="list-group">
        <li class="list-group-item">
            <h5>General Purpose Melee</h5>
            <img src="/images/action_bars/melee++.jpg">
        </li>
        <li class="list-group-item">
            <h5>General Purpose Range</h5>
            <img src="/images/action_bars/range++.jpg">
        </li>
        <li class="list-group-item">
            <h5>General Purpose Mage</h5>
            <img src="/images/action_bars/mage++.jpg">
        </li>
        <li class="list-group-item">
            <h5>
                Gregorovic
                <small class="text-muted">Requires Masterwork Spear</small>
            </h5>
            <img src="/images/action_bars/greg++.jpg">
        </li>
        <li class="list-group-item">
            <h5>Corrupted Creatures/Soul Devourers</h5>
            <img src="/images/action_bars/soul_devourers++.jpg">
        </li>
        <li class="list-group-item">
            <h5>
                Moss Golems
                <small class="text-muted">Requires halberd/cannon</small>
            </h5>
            <img src="/images/action_bars/moss_golems++.jpg">
        </li>
    </ul>

</div>