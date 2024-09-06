<?php

use yii\db\Migration;

/**
 * Class m201004_181305_create_event_tables
 */
class m201004_181305_create_event_tables extends Migration
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

        $this->createTable('runescape_event', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
            'description' => $this->text()->notNull(),
            'host_id' => $this->integer()->notNull(),
            'cohost_id' => $this->integer(),
            'start_date' => $this->dateTime()->notNull(),
            'end_date' => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->createTable('runescape_event_user', [
            'id' => $this->primaryKey(),
            'event_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ], $tableOptions);

        // creates index for column `host_id`
        $this->createIndex(
            'idx-runescape_event-host_id',
            'runescape_event',
            'host_id'
        );

        // add foreign key for table `runescape_event`
        $this->addForeignKey(
            'fk-runescape_event-host_id',
            'runescape_event',
            'host_id',
            'runescape_user',
            'id',
            'CASCADE'
        );

        // creates index for column `cohost_id`
        $this->createIndex(
            'idx-runescape_event-cohost_id',
            'runescape_event',
            'host_id'
        );

        // add foreign key for table `runescape_event`
        $this->addForeignKey(
            'fk-runescape_event-cohost_id',
            'runescape_event',
            'cohost_id',
            'runescape_user',
            'id',
            'CASCADE'
        );

        // creates index for column `event_id`
        $this->createIndex(
            'idx-runescape_event_user-event_id',
            'runescape_event_user',
            'user_id'
        );

        // add foreign key for table `runescape_event_user`
        $this->addForeignKey(
            'fk-runescape_event_user-event_id',
            'runescape_event_user',
            'event_id',
            'runescape_event',
            'id',
            'CASCADE'
        );

        // creates index for column `user_id`
        $this->createIndex(
            'idx-runescape_event_user-user_id',
            'runescape_event_user',
            'user_id'
        );

        // add foreign key for table `runescape_event_user`
        $this->addForeignKey(
            'fk-runescape_event_user-user_id',
            'runescape_event_user',
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
        // drops foreign key for table `runescape_event_user`
        $this->dropForeignKey(
            'fk-runescape_event_user-user_id',
            'runescape_event_user'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-runescape_event_user-user_id',
            'runescape_event_user'
        );

        // drops foreign key for table `runescape_event_user`
        $this->dropForeignKey(
            'fk-runescape_event_user-event_id',
            'runescape_event_user'
        );

        // drops index for column `event_id`
        $this->dropIndex(
            'idx-runescape_event_user-event_id',
            'runescape_event_user'
        );

        // drops foreign key for table `runescape_event`
        $this->dropForeignKey(
            'fk-runescape_event-host_id',
            'runescape_event'
        );

        // drops index for column `host_id`
        $this->dropIndex(
            'idx-runescape_event-host_id',
            'runescape_event'
        );

        // drops foreign key for table `runescape_event`
        $this->dropForeignKey(
            'fk-runescape_event-cohost_id',
            'runescape_event'
        );

        // drops index for column `cohost_id`
        $this->dropIndex(
            'idx-runescape_event-cohost_id',
            'runescape_event'
        );

        $this->dropTable('runescape_event_user');
        $this->dropTable('runescape_event');
    }
}
