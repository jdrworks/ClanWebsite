<?php

use yii\db\Migration;

/**
 * Class m200710_215736_logging_tables
 */
class m200710_215736_logging_tables extends Migration
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

        $this->createTable('runescape_rank', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull()->unique(),
        ], $tableOptions);

        $this->createTable('runescape_user', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique(),
            'rank_id' => $this->integer()->notNull(),
            'capped' => $this->boolean()->notNull()->defaultValue(false),
            'visited' => $this->boolean()->notNull()->defaultValue(false),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);

        // creates index for column `rank_id`
        $this->createIndex(
            'idx-runescape_user-rank_id',
            'runescape_user',
            'rank_id'
        );

        // add foreign key for table `runescape_rank`
        $this->addForeignKey(
            'fk-runescape_user-rank_id',
            'runescape_user',
            'rank_id',
            'runescape_rank',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `runescape_user`
        $this->dropForeignKey(
            'fk-runescape_user-rank_id',
            'runescape_user'
        );

        // drops index for column `rank_id`
        $this->dropIndex(
            'idx-runescape_user-rank_id',
            'runescape_user'
        );

        $this->dropTable('runescape_user');
        $this->dropTable('runescape_rank');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200710_215736_logging_tables cannot be reverted.\n";

        return false;
    }
    */
}
