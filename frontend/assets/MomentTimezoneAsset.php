<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class MomentTimezoneAsset extends AssetBundle
{
    public $sourcePath = '@npm/moment-timezone';
    public $js = [
        'moment-timezone.js',
    ];
}
