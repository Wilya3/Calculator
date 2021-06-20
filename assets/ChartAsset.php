<?php


namespace app\assets;

use yii\web\AssetBundle;
use yii\web\View;

class ChartAsset extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'https://cdn.jsdelivr.net/npm/apexcharts',
        'js/chart.js',
        'js/data_preparator.js',
        'js/date_changing.js'
    ];
}