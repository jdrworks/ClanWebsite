<?php

use common\models\RunescapeRank;
use yii\db\Migration;

/**
 * Class m200830_173128_rank_and_user_updates
 */
class m200830_173128_rank_and_user_updates extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('runescape_rank', 'rank_points', $this->integer());
        $this->addColumn('runescape_rank', 'promotion_points', $this->integer());
        foreach (RunescapeRank::find()->all() as $runescapeRank) {
            if ($runescapeRank->name === 'Recruit') {
                $runescapeRank->rank_points = 0;
                $runescapeRank->promotion_points = 4;
                $runescapeRank->save();
            }
            if ($runescapeRank->name === 'Corporal') {
                $runescapeRank->rank_points = 4;
                $runescapeRank->promotion_points = 8;
                $runescapeRank->save();
            }
            if ($runescapeRank->name === 'Sergeant') {
                $runescapeRank->rank_points = 8;
                $runescapeRank->promotion_points = 16;
                $runescapeRank->save();
            }
            if ($runescapeRank->name === 'Lieutenant') {
                $runescapeRank->rank_points = 16;
                $runescapeRank->promotion_points = 32;
                $runescapeRank->save();
            }
            if ($runescapeRank->name === 'Captain') {
                $runescapeRank->rank_points = 32;
                $runescapeRank->promotion_points = 64;
                $runescapeRank->save();
            }
            if ($runescapeRank->name === 'General') {
                $runescapeRank->rank_points = 64;
                $runescapeRank->save();
            }
        }

        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('runescape_user_note', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'creator_id' => $this->integer()->notNull(),
            'note' => $this->text()->notNull(),
            'created_at' => $this->datetime()->notNull(),
        ], $tableOptions);

        // creates index for column `user_id`
        $this->createIndex(
            'idx-runescape_user_note-user_id',
            'runescape_user_note',
            'user_id'
        );

        // add foreign key for table `runescape_user`
        $this->addForeignKey(
            'fk-runescape_user_note-user_id',
            'runescape_user_note',
            'user_id',
            'runescape_user',
            'id',
            'CASCADE'
        );

        // creates index for column `creator_id`
        $this->createIndex(
            'idx-runescape_user_note-creator_id',
            'runescape_user_note',
            'creator_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-runescape_user_note-creator_id',
            'runescape_user_note',
            'creator_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-runescape_user_note-creator_id',
            'runescape_user_note'
        );

        // drops index for column `creator_id`
        $this->dropIndex(
            'idx-runescape_user_note-creator_id',
            'runescape_user_note'
        );

        // drops foreign key for table `runescape_user`
        $this->dropForeignKey(
            'fk-runescape_user_note-user_id',
            'runescape_user_note'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-runescape_user_note-user_id',
            'runescape_user_note'
        );

        $this->dropTable('runescape_user_note');

        $this->dropColumn('runescape_rank', 'promotion_points');
        $this->dropColumn('runescape_rank', 'rank_points');
    }
}
