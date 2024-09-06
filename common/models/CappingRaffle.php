<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "runescape_capping_raffle".
 *
 * @property int $id
 * @property string $reset_at
 * @property int $winner_id
 * @property int $paid
 *
 * @property RunescapeUser $winner
 * @property RunescapeUser[] $runescapeCappingRaffleUsers
 */
class CappingRaffle extends \yii\db\ActiveRecord
{
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
        return 'runescape_capping_raffle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reset_at'], 'required'],
            [['reset_at'], 'safe'],
            [['winner_id', 'paid'], 'integer'],
            [['winner_id'], 'exist', 'skipOnError' => true, 'targetClass' => RunescapeUser::className(), 'targetAttribute' => ['winner_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'reset_at' => 'Reset At',
            'winner_id' => 'Winner ID',
            'paid' => 'Paid',
        ];
    }

    /**
     * Gets query for [[Winner]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWinner()
    {
        return $this->hasOne(RunescapeUser::class, ['id' => 'winner_id']);
    }

    /**
     * Gets query for [[RunescapeCappingRaffleUsers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRunescapeUsers()
    {
        return $this->hasMany(RunescapeUser::class, ['id' => 'user_id'])
            ->viaTable('runescape_capping_raffle_user', ['capping_raffle_id' => 'id']);
    }

    public function pickRaffleWinner()
    {
        $oldWInnerId = null;
        $winner = null;
        $cappingIds = [];

        foreach ($this->runescapeUsers as $runescapeUser) {
            if (!in_array($runescapeUser->rank_id, [1,2,3])) {
                $cappingIds[] = $runescapeUser->id;
            }
        }


        if (!empty($this->winner_id)) {
            $oldWInnerId = $this->winner_id;
            $this->winner_id = null;
        }

        while (empty($this->winner_id) && !empty($cappingIds)) {
            $winner = $cappingIds[array_rand($cappingIds, 1)];
            if ($winner != $oldWInnerId) {
                $this->winner_id = $winner;
                if (!$this->save()) {
                    return false;
                }

                return true;
            }
        }

        return false;
    }

    public static function getLatestRaffle()
    {
        return self::find()->orderBy(['reset_at' => SORT_DESC])->one();
    }
}
