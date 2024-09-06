<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "runescape_rank".
 *
 * @property int $id
 * @property string $name
 * @property int x`$rank_points
 * @property int $promotion_points
 *
 * @property RunescapeUser[] $runescapeUsers
 */
class RunescapeRank extends \yii\db\ActiveRecord
{
    const RECRUIT_POINTS = 0;
    const CORPORAL_POINTS = 4;
    const SERGEANT_POINTS = 8;
    const LIEUTENANT_POINTS = 16;
    const CAPTAIN_POINTS = 32;
    const GENERAL_POINTS = 64;
    const ADMIN_POINTS = 777;
    const OVERSEER_POINTS = 999;

    public function behaviors()
    {
        return [
            'bedezign\yii2\audit\AuditTrailBehavior'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'runescape_rank';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['rank_points', 'promotion_points'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'rank_points' => 'Rank Points',
            'promotion_points' => 'Promotion Points',
        ];
    }

    /**
     * Gets query for [[RunescapeUsers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRunescapeUsers()
    {
        return $this->hasMany(RunescapeUser::className(), ['rank_id' => 'id']);
    }
}
