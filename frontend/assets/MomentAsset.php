<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class MomentAsset extends AssetBundle
{
    public $sourcePath = '@npm/moment';
    public $js = [
        'moment.js'
    ];
}
