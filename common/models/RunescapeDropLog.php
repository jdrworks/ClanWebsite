<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "runescape_drop_log".
 *
 * @property int $id
 * @property int $user_id
 * @property int $item_id
 * @property string $dropped_at
 *
 * @property RunescapeItem $item
 * @property RunescapeUser $user
 */
class RunescapeDropLog extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'runescape_drop_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'item_id', 'dropped_at'], 'required'],
            [['user_id', 'item_id'], 'integer'],
            [['dropped_at'], 'safe'],
            [['item_id'], 'exist', 'skipOnError' => true, 'targetClass' => RunescapeItem::className(), 'targetAttribute' => ['item_id' => 'id']],
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
            'item_id' => 'Item ID',
            'dropped_at' => 'Dropped At',
        ];
    }

    /**
     * Gets query for [[Item]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(RunescapeItem::className(), ['id' => 'item_id']);
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
