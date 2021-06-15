<?php


namespace app\assets;

use yii\web\AssetBundle;

class ChartCategoriesAsset extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/create_chart_categories.js'
    ];
    public $depends = [
        'app\assets\ChartAsset'
    ];
}