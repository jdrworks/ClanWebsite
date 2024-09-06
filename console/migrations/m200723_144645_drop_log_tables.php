<?php

use yii\db\Migration;

/**
 * Class m200723_144645_drop_log_tables
 */
class m200723_144645_drop_log_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('runescape_drop_log', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'item_id' => $this->integer()->notNull(),
            'dropped_at' => $this->datetime()->notNull(),
        ], $tableOptions);

        $this->createTable('runescape_item', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
        ], $tableOptions);

        // creates index for column `user_id`
        $this->createIndex(
            'idx-runescape_drop_log-user_id',
            'runescape_drop_log',
            'user_id'
        );

        // add foreign key for table `runescape_user`
        $this->addForeignKey(
            'fk-runescape_drop_log-user_id',
            'runescape_drop_log',
            'user_id',
            'runescape_user',
            'id',
            'CASCADE'
        );

        // creates index for column `item_id`
        $this->createIndex(
            'idx-runescape_drop_log-item_id',
            'runescape_drop_log',
            'item_id'
        );

        // add foreign key for table `runescape_item`
        $this->addForeignKey(
            'fk-runescape_drop_log-item_id',
            'runescape_drop_log',
            'item_id',
            'runescape_item',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `runescape_item`
        $this->dropForeignKey(
            'fk-runescape_drop_log-item_id',
            'runescape_drop_log'
        );

        // drops index for column `item_id`
        $this->dropIndex(
            'idx-runescape_drop_log-item_id',
            'runescape_drop_log'
        );

        // drops foreign key for table `runescape_user`
        $this->dropForeignKey(
            'fk-runescape_drop_log-user_id',
            'runescape_drop_log'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-runescape_drop_log-user_id',
            'runescape_drop_log'
        );

        $this->dropTable('runescape_item');
        $this->dropTable('runescape_drop_log');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200723_144645_drop_log_tables cannot be reverted.\n";

        return false;
    }
    */
}
