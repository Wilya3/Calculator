<?php
if (Yii::$app->session->hasFlash('error')) {
    $flash = Yii::$app->session->getFlash('error');
} ?>
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>


<h3>Графики</h3>
<div id="chart"></div>


<?php
//TODO: Не нужно ли вызывать action? Это MVC не противоречит?
include_once __DIR__ . '/../charge/index.php';
include_once __DIR__ . '/../category/index.php';
?>


<script>
    // All these constants are required to create charts. They are used in closures. Forbidden to rename!
    // Each constant represents corresponding table.
    const categories = <?= json_encode($categories) ?>;
    const charges = <?= json_encode($charges) ?>;
    const user_category = <?= json_encode($user_category) ?>;
</script>
