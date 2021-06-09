<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm; ?>


<?php
    if (Yii::$app->session->hasFlash('error')) {
        $flash = Yii::$app->session->getFlash('error');
    }
?>


<?php if (count($charges) > 0): ?>
    <table class="table">
        <h3>Расходы/доходы</h3>
        <tr>
            <th>Название</th>
            <th>Описание</th>
            <th>Сумма</th>
            <th>Дата</th>
            <th>Категория</th>
            <th></th>
            <th></th>
        </tr>
        <?php foreach($charges as $row): ?>
        <?php foreach($user_category as $uc) {
                if ($uc['id'] == $row['user_category_id']) {
                    foreach($categories as $category) {
                        if ($category['id'] == $uc['category_id']) {
                            $category_name = $category['name'];
                        }
                    }
                } // TODO: Спросить про эту порнографию
            } ?>
        <tr>
            <td> <?= $row['name'] ?> </td>
            <td> <?= $row['description'] ?> </td>
            <td> <?= $row['amount'] ?> </td>
            <td> <?= $row['date'] ?> </td>
            <td> <?= $category_name ?> </td>
            <td> <a href="/charge/charge-update?id=<?= $row['id'] ?>">Изменить</a> </td>
            <td> <a href="/charge/charge-delete?id=<?= $row['id'] ?>">Удалить</a> </td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <h3>У Вас пока нет записей</h3>
<?php endif; ?>

<div>
    <a href="/charge/charge-add" class="btn btn-success">Добавить запись</a>
</div>
