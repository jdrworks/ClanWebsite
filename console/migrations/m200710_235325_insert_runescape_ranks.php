<?php

use yii\db\Migration;

/**
 * Class m200710_235325_insert_runescape_ranks
 */
class m200710_235325_insert_runescape_ranks extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->batchInsert('runescape_rank', ['name'], [
            ['Owner'],
            ['Deputy Owner'],
            ['Overseer'],
            ['Coordinator'],
            ['Organiser'],
            ['Admin'],
            ['General'],
            ['Captain'],
            ['Lieutenant'],
            ['Sergeant'],
            ['Corporal'],
            ['Recruit'],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete('runescape_rank', 1);
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200710_235325_insert_runescape_ranks cannot be reverted.\n";

        return false;
    }
    */
}
