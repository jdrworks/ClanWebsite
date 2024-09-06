<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "runescape_item".
 *
 * @property int $id
 * @property int $name
 *
 * @property RunescapeDropLog[] $runescapeDropLogs
 */
class RunescapeItem extends \yii\db\ActiveRecord
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
        return 'runescape_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
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
        ];
    }

    /**
     * Gets query for [[RunescapeDropLogs]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRunescapeDropLogs()
    {
        return $this->hasMany(RunescapeDropLog::class, ['item_id' => 'id']);
    }

    public function getUrlName()
    {
        $name = $this->name;
        // Item type fixes
        if (strpos($name, 'pet') !== false) {
            // Check if this is for a pet
            $array = explode(',', $this->name);
            $name = $array[0] . '_pet';
        } elseif (strpos($name, 'book:') !== false) {
            // Check if this is a book
            $name = str_replace('book: ', '', $this->name);
        } elseif (strpos($name, 'page from') !== false) {
            // Check if this is a page
            $name = str_replace('page from ', '', $this->name) . ' page';
        } elseif (strpos($name, 'off-hand') !== false) {
                // Check if this is an off-hand
            $name = str_replace('off-hand', 'Off-hand', $this->name);
        }

        // Item specific fixes
        if (strpos($name, 'Shadow Glaive') !== false) {
            $name = str_replace('Shadow Glaive', 'shadow glaive', $name);
        }

        // Final replace
        return str_replace(' ', '_', $name);
    }

    public function getFullUrl()
    {
        $filename = Yii::getAlias('@frontendDomain/images/items/') . $this->urlName . '.png';

        $headers = get_headers($filename);
        if (stripos($headers[0],"200 OK")) {
            return $filename;
        }

        return '';
    }
}
