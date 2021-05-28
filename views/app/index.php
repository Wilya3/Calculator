<?php
?>
<h1>Добро пожаловать!</h1>

<!--<style>-->
<!--    td {-->
<!--        border: 2px solid gray;-->
<!--    }-->
<!--</style>-->

<table class="table">
    <tr>
        <th>Столбец</th>
        <th>Столбец</th>
        <th>Столбец</th>
    </tr>
    <?php foreach($table as $row): ?>
    <tr>
        <td> <?= $row[''] ?> </td>
        <td> <?= $row[''] ?> </td>
        <td> <?= $row[''] ?> </td>
    </tr>
    <?php endforeach; ?>
</table>
<p>Где-то при помощи жс должна вываливаться формочка для добавления данных</p>
