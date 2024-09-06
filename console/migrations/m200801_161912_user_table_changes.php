<?php

use yii\db\Migration;

/**
 * Class m200801_161912_user_table_changes
 */
class m200801_161912_user_table_changes extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('runescape_user', 'in_clan', $this->boolean()->notNull()->defaultValue(true));
        $this->addColumn('runescape_user', 'old_name', $this->boolean()->notNull()->defaultValue(false));

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('runescape_user_name', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ], $tableOptions);

        // creates index for column `user_id`
        $this->createIndex(
            'idx-runescape_user_name-user_id',
            'runescape_user_name',
            'user_id'
        );

        // add foreign key for table `runescape_user_name`
        $this->addForeignKey(
            'fk-runescape_user_name-user_id',
            'runescape_user_name',
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
        // drops foreign key for table `runescape_user_name`
        $this->dropForeignKey(
            'fk-runescape_user_name-user_id',
            'runescape_user_name'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-runescape_user_name-user_id',
            'runescape_user_name'
        );

        $this->dropTable('runescape_user_name');

        $this->dropColumn('runescape_user', 'in_clan');
        $this->dropColumn('runescape_user', 'old_name');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200801_161912_user_table_changes cannot be reverted.\n";

        return false;
    }
    */
}
