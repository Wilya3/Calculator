<?php

use app\assets\ChartAsset;
use kartik\date\DatePicker;

ChartAsset::register($this);

if (Yii::$app->session->hasFlash('error')) {
    $flash = Yii::$app->session->getFlash('error');
}?>


<h1>Добро пожаловать, <?= Yii::$app->user->identity->username; ?>!</h1>

<style>.col{ display: inline-block; }</style>
<div class="row">
    <div id="startDate" class="col" style="width:30%">
        <?= DatePicker::widget([
            'name' => 'start_date',
            'value' => date("Y-m-d", strtotime("-1 month")),
            'type' => DatePicker::TYPE_COMPONENT_APPEND,
            'language' => 'ru',
            'pluginOptions' => [
                'autoclose'=>true,
                'format' => 'yyyy-mm-dd',
                'startDate' => "2000-01-01",
                'endDate' => date("Y-m-d"),
            ],
            'options' => [
                'id' => 'start_date'
            ]
        ]);?>
    </div>
    <div id="endDate" class="col" style="width:30%; position=">
        <?= DatePicker::widget([
            'name' => 'end_date',
            'value' => date("Y-m-d"),
            'type' => DatePicker::TYPE_COMPONENT_APPEND,
            'language' => 'ru',
            'pluginOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
                'startDate' => "2000-01-01",
                'endDate' => date("Y-m-d"),
            ],
            'options' => [
                'id' => 'end_date'
            ]
        ]); ?>
    </div>
</div>
<div id="chart"></div>


<!--Render table of categories-->
<h2>Категории</h2>
<div id="categories"></div>
<script>$("#categories").load("http://calculator/category/index");</script>


<!--Data for charts render-->
<script>
    // All these constants are required to create charts. Forbidden to rename!
    // Each constant represents corresponding table.
    const categories = <?= json_encode($categories) ?>;
    const charges = <?= json_encode($charges) ?>;
    const user_category = <?= json_encode($user_category) ?>;
</script>
