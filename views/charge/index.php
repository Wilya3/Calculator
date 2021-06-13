<?php
if (Yii::$app->session->hasFlash('error')) {
    $flash = Yii::$app->session->getFlash('error');
}
?>

<?php
if (!is_null($category)) {
    echo "<h2>{$category['name']}</h2>";
    echo "<p>{$category['description']}</p>";
}
?>

<h3>Расходы/доходы</h3>
<?php
include_once 'charge_grid_view.php';
?>
