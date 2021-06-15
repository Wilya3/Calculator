<?php

use app\assets\ChartChargesAsset;

ChartChargesAsset::register($this);


if (Yii::$app->session->hasFlash('error')) {
    $flash = Yii::$app->session->getFlash('error');
}



echo "<h2>{$category['name']}</h2>";
echo "<p>{$category['description']}</p>";
echo "<script>const charges =" . json_encode($charges) . " </script>";
?>
<div id="chart"></div>

<h3>Расходы/доходы</h3>
<?php
include_once 'charge_grid_view.php';
?>
