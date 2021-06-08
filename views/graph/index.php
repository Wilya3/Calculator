
<?php
if (Yii::$app->session->hasFlash('error')) {
    $flash = Yii::$app->session->getFlash('error');
}
?>

<h3>Графики</h3>




<?php
//TODO: Не нужно ли вызывать action? Это MVC не противоречит?
include_once __DIR__ . '/../charge/index.php';
include_once __DIR__ . '/../category/index.php';
