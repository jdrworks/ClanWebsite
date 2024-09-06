<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "runescape_user_note".
 *
 * @property int $id
 * @property int $user_id
 * @property int $creator_id
 * @property string $note
 * @property string $created_at
 *
 * @property RunescapeUser $creator
 * @property RunescapeUser $user
 */
class RunescapeUserNote extends \yii\db\ActiveRecord
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
        return 'runescape_user_note';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'creator_id', 'note', 'created_at'], 'required'],
            [['user_id', 'creator_id'], 'integer'],
            [['note'], 'string'],
            [['created_at'], 'safe'],
            [['creator_id'], 'exist', 'skipOnError' => true, 'targetClass' => RunescapeUser::className(), 'targetAttribute' => ['creator_id' => 'id']],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => RunescapeUser::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'creator_id' => 'Creator ID',
            'note' => 'Note',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[Creator]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'creator_id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(RunescapeUser::className(), ['id' => 'user_id']);
    }
}
