<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "runescape_user_name".
 *
 * @property int $id
 * @property string $username
 * @property int $user_id
 *
 * @property RunescapeUser $user
 */
class RunescapeUsername extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'runescape_user_name';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'user_id'], 'required'],
            [['username'], 'safe'],
            [['user_id'], 'integer'],
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
            'username' => 'Username',
            'user_id' => 'User ID',
        ];
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
