<?php

use app\assets\ChartAsset;

ChartAsset::register($this);

if (Yii::$app->session->hasFlash('error')) {
    $flash = Yii::$app->session->getFlash('error');
}?>


<h1>Добро пожаловать, <?= Yii::$app->user->identity->username; ?>!</h1>
<div id="chart"></div>


<!--Render table of categories-->
<h2>Категории</h2>
<div id="categories"></div>
<script>$("#categories").load("http://calculator/category/index");</script>


<!--Data for charts render-->
<script>
    // All these constants are required to create charts. Forbidden to rename!
    // Each constant represents corresponding table.
    const categories = <?= json_encode($categories) ?>//;
    const charges = <?= json_encode($charges) ?>;
    const user_category = <?= json_encode($user_category) ?>;
</script>
