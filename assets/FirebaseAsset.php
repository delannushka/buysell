<?php

namespace app\assets;

use yii\web\AssetBundle;

class FirebaseAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $js = [
        'js/firebase.js'
    ];

    public $jsOptions = [
        'type' => 'module',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap5\BootstrapAsset'
    ];
}
