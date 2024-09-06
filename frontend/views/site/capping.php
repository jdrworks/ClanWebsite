<?php

/* @var $this yii\web\View */

use yii\helpers\Html;

$this->title = 'Citadel Capping Guide';
?>
<div class="site-rules">
    <h1><?= Html::encode($this->title) ?></h1>

    <h3>What is Capping?</h3>
    <p>
        Each clan can have a citadel which offers a variety of features, including
        <a href="https://runescape.wiki/w/Clan_Citadel#Skill_plots" target="_blank" rel="noopener noreferrer">
            skilling plots
        </a> for experience, party room, avatar habitat, and more. In order to upgrade the citadel to unlock more features
        like different skilling plots, we need resources from the various skilling plots. Each week, clan members can
        skill at the various plots in the citadel earning resources for the clan, up to their resource <em>cap</em>.
        Hitting this <em>cap</em> is known as <strong>capping</strong>.
    </p>

    <h4>Why Should I cap?</h4>
    <p>
        Skilling at the citadel offers some of the best experience rates available. In addition, after you cap you can
        activate a buff that lasts for a week giving you 3-6% bonus experience for everything you do. This buff starts
        at 3% and increases by 1% per
        <a href="https://runescape.wiki/w/Clan_Citadel#Fealty" target="_blank" rel="noopener noreferrer">
            fealty
        </a> and maxes at 3 fealty for a total of 6% experience. Also, at 3 fealty you can claim bonus experience from the
        <a href="https://runescape.wiki/w/Clan_cloak" target="_blank" rel="noopener noreferrer">Clan Cloak</a> once per
        week. The <a href="https://runescape.wiki/w/Quartermaster_(Clan_Citadels)" target="_blank" rel="noopener noreferrer">
            Quartermaster
        </a> will also give you bonus experience in one of the skills for which there is a skilling plot, based on the
        plot's tier. Lastly, we hold a raffle each week for everyone who caps. A winner is chosen at random to win
        20 Million GP. In order to be entered for a chance to win, your Rune Metrics profile must be public. To check if
        your profile is public, as well as how to make it public if it's not, please check the
        <?= Html::a('Clan Roster', ['/roster/index']) ?>.
    </p>

    <h4>How To Cap</h4>
    <ol>
        <li>
            Head to the clan citadel. There are two ways to get there. The first is through the clan camp south of Falador
            and the second is through the clan portal in Prifddinas:
            <img src="/images/capping_instructions_1.jpg" class="img-fluid">
            <img src="/images/capping_instructions_2.jpg" class="img-fluid">
        </li>
        <li>
            Once in the citadel, you should head to the avatar habitat to activate the skill plot boost. This buff will
            allow you to cap faster:
            <img src="/images/capping_instructions_3.jpg" class="img-fluid">
            <img src="/images/capping_instructions_4.jpg" class="img-fluid">
            <em class="text-muted">Note: You can use the map to quickly teleport around the citadel.</em>
        </li>
        <li>
            You can skill at any open skilling plot, as ones that we have enough resources for are automatically locked.
            If you do not need to train a specific skill, we request that you check the resources menu to find out what
            resource(s) we need the most of:
            <img src="/images/capping_instructions_5.jpg" class="img-fluid">
            <img src="/images/capping_instructions_6.jpg" class="img-fluid">
            <em class="text-muted">
                Note: The summoning plot only offers a limited amount of experience per week, and does not generate
                resources for the clan.
            </em>
        </li>
        <li>
            You can keep track of your current and total resources gathered here:
            <div class="text-center">
                <img src="/images/capping_instructions_7.jpg" class="img-fluid">
            </div>
        </li>
        <li>
            Once you've hit your resource limit, a clan broadcast will go out and you can claim your rewards. Be sure to
            go back to the avatar habitat to activate your weekly experience buff:
            <img src="/images/capping_instructions_8.jpg" class="img-fluid">
        </li>
        <li>
            After capping you can also visit the quartermaster in the keep for bonus experience, and interact with the
            clan cloak for bonus experience in any skill:
            <img src="/images/capping_instructions_9.jpg" class="img-fluid">
            <img src="/images/capping_instructions_10.jpg" class="img-fluid">
        </li>
        <li>
            Because the citadel exists on a "shard world" (a different server) you cannot teleport directly out. Instead
            you need to return to the entrance and leave through the portal:
            <img src="/images/capping_instructions_11.jpg" class="img-fluid">
        </li>
    </ol>
</div>