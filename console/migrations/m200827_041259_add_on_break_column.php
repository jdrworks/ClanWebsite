<?php

use yii\db\Migration;

/**
 * Class m200827_041259_add_on_break_column
 */
class m200827_041259_add_on_break_column extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('runescape_user', 'on_break', $this->date());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('runescape_user', 'on_break');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200827_041259_add_on_break_column cannot be reverted.\n";

        return false;
    }
    */
}
