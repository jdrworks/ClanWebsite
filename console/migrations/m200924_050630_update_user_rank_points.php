<?php

use yii\db\Migration;

/**
 * Class m200924_050630_update_user_rank_points
 */
class m200924_050630_update_user_rank_points extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->alterColumn('runescape_user', 'rank_points', 'integer DEFAULT 0');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->alterColumn('runescape_user', 'rank_points', 'integer NOT NULL DEFAULT 0');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200924_050630_update_user_rank_points cannot be reverted.\n";

        return false;
    }
    */
}
