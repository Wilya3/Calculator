<?php
/**
 * @var $category app\models\Category
 * @var $charges app\models\Charge
 */
use app\assets\ChartChargesAsset;
use kartik\date\DatePicker;

ChartChargesAsset::register($this);


if (Yii::$app->session->hasFlash('error')) {
    $flash = Yii::$app->session->getFlash('error');
}



echo "<h2>{$category['name']}</h2>";
echo "<p>{$category['description']}</p>";
echo "<script>const charges =" . json_encode($charges) . " </script>";
?>
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
<div id="chart" style="width='100%'"></div>

<h3>Расходы/доходы</h3>
<?php
include_once 'charge_grid_view.php';
?>
