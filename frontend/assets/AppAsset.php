<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
//    public $basePath = '@webroot';
//    public $baseUrl = '@web';

    public $sourcePath = '@frontend/assets/dist/';

    public $css = [
        'css/vendors.min.css',
        'css/app.min.css'
    ];
    public $js = [
        'js/config.js',
        'js/vendors.min.js',
        'js/app.js'
    ];
    public $depends = [
//        'yii\web\JqueryAsset',
//        'yii\web\YiiAsset',
//        'yii\bootstrap\BootstrapAsset',
    ];
}
