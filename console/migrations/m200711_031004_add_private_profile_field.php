<?php

use yii\db\Migration;

/**
 * Class m200711_031004_add_private_profile_field
 */
class m200711_031004_add_private_profile_field extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('runescape_user', 'private_profile', $this->boolean()->notNull()->defaultValue(false));
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('runescape_user', 'private_profile');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200711_031004_add_private_profile_field cannot be reverted.\n";

        return false;
    }
    */
}
