<?php

use yii\db\Migration;

/**
 * Class m200716_152506_capping_raffle_tables
 */
class m200716_152506_capping_raffle_tables extends Migration
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

        $this->createTable('runescape_capping_raffle', [
            'id' => $this->primaryKey(),
            'reset_at' => $this->datetime()->notNull(),
            'winner_id' => $this->integer()->notNull(),
            'paid' => $this->boolean()->notNull()->defaultValue(false),
        ], $tableOptions);

        $this->createTable('runescape_capping_raffle_user', [
            'id' => $this->primaryKey(),
            'capping_raffle_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ], $tableOptions);

        // creates index for column `winner_id`
        $this->createIndex(
            'idx-runescape_capping_raffle-winner_id',
            'runescape_capping_raffle',
            'winner_id'
        );

        // add foreign key for table `runescape_user`
        $this->addForeignKey(
            'fk-runescape_capping_raffle-winner_id',
            'runescape_capping_raffle',
            'winner_id',
            'runescape_user',
            'id',
            'CASCADE'
        );

        // creates index for column `capping_raffle_id`
        $this->createIndex(
            'idx-runescape_capping_raffle_user-capping_raffle_id',
            'runescape_capping_raffle_user',
            'capping_raffle_id'
        );

        // add foreign key for table `runescape_capping_raffle`
        $this->addForeignKey(
            'fk-runescape_capping_raffle_user-capping_raffle_id',
            'runescape_capping_raffle_user',
            'capping_raffle_id',
            'runescape_capping_raffle',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            'idx-runescape_capping_raffle_user-user_id',
            'runescape_capping_raffle_user',
            'user_id'
        );

        // add foreign key for table `runescape_user`
        $this->addForeignKey(
            'fk-runescape_capping_raffle_user-user_id',
            'runescape_capping_raffle_user',
            'user_id',
            'runescape_user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `runescape_capping_raffle_user`
        $this->dropForeignKey(
            'fk-runescape_capping_raffle_user-user_id',
            'runescape_capping_raffle_user'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-runescape_capping_raffle_user-user_id',
            'runescape_capping_raffle_user'
        );

        // drops foreign key for table `runescape_capping_raffle_user`
        $this->dropForeignKey(
            'fk-runescape_capping_raffle_user-capping_raffle_id',
            'runescape_capping_raffle_user'
        );

        // drops index for column `capping_raffle_id`
        $this->dropIndex(
            'idx-runescape_capping_raffle_user-capping_raffle_id',
            'runescape_capping_raffle_user'
        );

        // drops foreign key for table `runescape_capping_raffle`
        $this->dropForeignKey(
            'fk-runescape_capping_raffle-winner_id',
            'runescape_capping_raffle'
        );

        // drops index for column `winner_id`
        $this->dropIndex(
            'idx-runescape_capping_raffle-winner_id',
            'runescape_capping_raffle'
        );

        $this->dropTable('runescape_capping_raffle_user');
        $this->dropTable('runescape_capping_raffle');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200716_152506_capping_raffle_tables cannot be reverted.\n";

        return false;
    }
    */
}
