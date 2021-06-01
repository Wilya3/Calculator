<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm; ?>
<h1>
    Добро пожаловать,
    <?= Yii::$app->user->identity->username; ?>!
</h1>

<?php
    if (Yii::$app->session->hasFlash('error')) {
        $flash = Yii::$app->session->getFlash('error');
        echo '<div class="flash-error">' . $flash . "</div>";
    }
?>

<!--<style>-->
<!--    td {-->
<!--        border: 2px solid gray;-->
<!--    }-->
<!--</style>-->

<?php if (count($table) > 0): ?>
    <table class="table">
        <h3>Категории</h3>
        <tr>
            <th>Название</th>
            <th>Описание</th>
            <th></th>
        </tr>
        <?php foreach($table as $row): ?>
        <tr>
    <!--        <td> --><?//= $row->id ?><!--</td>-->
            <td> <?= $row->name ?> </td>
            <td> <?= $row->description ?> </td>
            <td> <a href="category-delete?id=<?= $row->id ?>">Удалить</a> </td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <h3>У Вас пока нет категорий</h3>
<?php endif; ?>

<div>
    <h3>Добавить категорию</h3>
    <?php
    $form = ActiveForm::begin(['options' => ['class'=>'form form-horizontal', 'id'=>'CategoryForm']]);
    echo $form->field($model, 'name')->label("Название");
    echo $form->field($model, 'description')->textarea()->label("Описание");
    echo Html::submitButton('Добавить', ['class'=>'btn btn-success']);
    ActiveForm::end();
    ?>
</div>
