<?php
    if (Yii::$app->session->hasFlash('error')) {
        $flash = Yii::$app->session->getFlash('error');
    }
?>
<h3><?=$category['name']?></h3>

<?php if (count($charges) > 0): ?>
    <table class="table">
        <h3>Расходы/доходы</h3>
        <tr>
            <th>Название</th>
            <th>Описание</th>
            <th>Сумма</th>
            <th>Дата</th>
            <th></th>
            <th></th>
        </tr>
        <?php foreach($charges as $row): ?>
        <tr>
            <td> <?= $row['name'] ?> </td>
            <td> <?= $row['description'] ?> </td>
            <td> <?= $row['amount'] ?> </td>
            <td> <?= $row['date'] ?> </td>
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
