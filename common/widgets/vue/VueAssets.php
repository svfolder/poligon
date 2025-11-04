<?php


namespace common\widgets\vue;

use yii\web\AssetBundle;

class VueAssets extends AssetBundle
{
    public $sourcePath = '@common/widgets/vue/assets';

    public $js = [
        'js/app.js',
        'js/chunk-vendors.js',
    ];

    public $jsOptions = ['position' => \yii\web\View::POS_HEAD];

    public $depends = ['yii\web\YiiAsset',];
}