<?php
namespace deka6pb\autoparser\assets;

use yii\web\AssetBundle;

class ModuleAsset extends AssetBundle {
    /**
     * @inheritdoc
     */
    public $sourcePath = '@deka6pb/autoparser/assets';

    public $css = [
        'css/main.css',
    ];
    public $js = [
        'js/main.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}