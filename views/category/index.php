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


<?php if (count($table) > 0): ?>
    <table class="table">
        <h3>Категории</h3>
        <tr>
            <th>Название</th>
            <th>Описание</th>
            <th></th>
            <th></th>
        </tr>
        <?php foreach($table as $row): ?>
        <tr>
            <td> <?= $row->name ?> </td>
            <td> <?= $row->description ?> </td>
            <td>
                <?php if ($row->is_default === 0): ?>
                <a href="category-update?id=<?= $row->id ?>">Изменить</a>
                <?php endif; ?>
            </td>

            <td> <a href="category-delete?id=<?= $row->id ?>">Удалить</a> </td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <h3>У Вас пока нет категорий</h3>
<?php endif; ?>

<div>
    <a href="category-add" class="btn btn-success">Добавить категорию</a>
</div>
