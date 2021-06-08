<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<?php

if (Yii::$app->session->hasFlash('error')) {
    $flash = Yii::$app->session->getFlash('error');
}
?>

<h3>Графики</h3>

<div id="chart"></div>



<?php
//TODO: Не нужно ли вызывать action? Это MVC не противоречит?
include_once __DIR__ . '/../charge/index.php';
include_once __DIR__ . '/../category/index.php';
?>


<script>
    var options = {
        title: {
          text: "Расходы"
        },
        chart: {
            type: 'line',
            height: 400
        },
        series: [{
            name: 'Дата',
            data: <?= json_encode($data['date_sum']['sum']) ?> //[10000,120.32]
        }],
        xaxis: {
            categories:  <?= json_encode($data['date_sum']['date']) ?> //["2021-06-06","2021-06-08"]
        }
    }

    var chart = new ApexCharts(document.querySelector("#chart"), options);

    chart.render();
</script>
