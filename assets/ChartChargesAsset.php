<?php


namespace app\assets;


use yii\web\AssetBundle;

class ChartChargesAsset extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/create_chart_charges.js'
    ];
    public $depends = [
        'app\assets\ChartAsset'
    ];
}