<?php

use yii\db\Migration;

/**
 * Class m200902_003230_raffle_updates
 */
class m200902_003230_raffle_updates extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('runescape_capping_raffle', 'winner_id', 'integer');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('runescape_capping_raffle', 'winner_id', 'integer not null');
    }
}
