<?php

use yii\db\Migration;

/**
 * Class m200812_202127_add_user_rank_tables
 */
class m200812_202127_add_user_rank_tables extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('runescape_user', 'rank_points', $this->integer()->notNull()->defaultValue(0));
        $this->addColumn('runescape_user', 'last_active', $this->dateTime()->notNull());
        $this->addColumn('runescape_user', 'total_xp', $this->bigInteger()->notNull()->defaultValue(0));

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('runescape_user_rank_point_log', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'reason' => $this->string()->notNull(),
            'changed_by' => $this->integer()->notNull(),
            'changed_at' => $this->datetime()->notNull(),
        ], $tableOptions);

        // creates index for column `user_id`
        $this->createIndex(
            'idx-runescape_user_rank_point_log-user_id',
            'runescape_user_rank_point_log',
            'user_id'
        );

        // add foreign key for table `runescape_user_rank_point_log`
        $this->addForeignKey(
            'fk-runescape_user_rank_point_log-user_id',
            'runescape_user_rank_point_log',
            'user_id',
            'runescape_user',
            'id',
            'CASCADE'
        );

        // creates index for column `changed_by`
        $this->createIndex(
            'idx-runescape_user_rank_point_log-changed_by',
            'runescape_user_rank_point_log',
            'changed_by'
        );

        // add foreign key for table `runescape_user_rank_point_log`
        $this->addForeignKey(
            'fk-runescape_user_rank_point_log-changed_by',
            'runescape_user_rank_point_log',
            'changed_by',
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
        // drops foreign key for table `runescape_user_name`
        $this->dropForeignKey(
            'fk-runescape_user_rank_point_log-changed_by',
            'runescape_user_rank_point_log'
        );

        // drops index for column `changed_by`
        $this->dropIndex(
            'idx-runescape_user_rank_point_log-changed_by',
            'runescape_user_rank_point_log'
        );

        // drops foreign key for table `runescape_user_name`
        $this->dropForeignKey(
            'fk-runescape_user_rank_point_log-user_id',
            'runescape_user_rank_point_log'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-runescape_user_rank_point_log-user_id',
            'runescape_user_rank_point_log'
        );

        $this->dropTable('runescape_user_rank_point_log');

        $this->dropColumn('runescape_user', 'total_xp');
        $this->dropColumn('runescape_user', 'last_active');
        $this->dropColumn('runescape_user', 'rank_points');
    }
}
