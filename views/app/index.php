<?php
?>
<h1>
    Добро пожаловать,
    <?= Yii::$app->user->identity->username; ?>!
</h1>

<!--<style>-->
<!--    td {-->
<!--        border: 2px solid gray;-->
<!--    }-->
<!--</style>-->

<table class="table">
    <h2>Категории</h2>
    <tr>
        <th>Название</th>
        <th>Описание</th>
    </tr>
    <?php foreach($table as $row): ?>
    <tr>
        <td> <?= $row->name ?> </td>
        <td> <?= $row->description ?> </td>
    </tr>
    <?php endforeach; ?>
</table>
<p>Где-то при помощи жс должна вываливаться формочка для добавления данных</p>
