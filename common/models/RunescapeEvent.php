<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "runescape_event".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $host_id
 * @property int|null $cohost_id
 * @property string $start_date
 * @property string $end_date
 *
 * @property RunescapeUser $cohost
 * @property RunescapeUser $host
 * @property RunescapeEventUser[] $runescapeEventUsers
 */
class RunescapeEvent extends \yii\db\ActiveRecord
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
        return 'runescape_event';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description', 'host_id', 'start_date', 'end_date'], 'required'],
            [['description'], 'string'],
            [['host_id', 'cohost_id'], 'integer'],
            [['start_date', 'end_date'], 'safe'],
            [['name'], 'string', 'max' => 255],
            [['name'], 'unique'],
            [['cohost_id'], 'exist', 'skipOnError' => true, 'targetClass' => RunescapeUser::className(), 'targetAttribute' => ['cohost_id' => 'id']],
            [['host_id'], 'exist', 'skipOnError' => true, 'targetClass' => RunescapeUser::className(), 'targetAttribute' => ['host_id' => 'id']],
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
            'description' => 'Description',
            'host_id' => 'Host ID',
            'cohost_id' => 'Cohost ID',
            'start_date' => 'Start Date',
            'end_date' => 'End Date',
        ];
    }

    /**
     * Gets query for [[Host]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getHost()
    {
        return $this->hasOne(RunescapeUser::class, ['id' => 'host_id']);
    }

    /**
     * Gets query for [[Cohost]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCohost()
    {
        return $this->hasOne(RunescapeUser::class, ['id' => 'cohost_id']);
    }

    /**
     * Gets query for [[RunescapeEventUsers]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRunescapeEventUsers()
    {
        return $this->hasMany(RunescapeEventUser::className(), ['event_id' => 'id']);
    }
}
