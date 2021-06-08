
<?php
    if (Yii::$app->session->hasFlash('error')) {
        $flash = Yii::$app->session->getFlash('error');
    }
?>


<?php if (count($categories) > 0): ?>
    <table class="table">
        <h3>Категории</h3>
        <tr>
            <th>Название</th>
            <th>Описание</th>
            <th></th>
            <th></th>
        </tr>
        <?php foreach($categories as $row): ?>
        <tr>
            <td> <?= $row['name']?> </td>
            <td> <?= $row['description'] ?> </td>
            <td>
                <?php if ($row['is_default'] === 0): ?>
                <a href="/category/category-update?id=<?= $row['id'] ?>">Изменить</a>
                <?php endif; ?>
            </td>

            <td> <a href="/category/category-delete?id=<?= $row['id'] ?>">Удалить</a> </td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <h3>У Вас пока нет категорий</h3>
<?php endif; ?>

<div>
    <a href="/category/category-add" class="btn btn-success">Добавить категорию</a>
</div>
