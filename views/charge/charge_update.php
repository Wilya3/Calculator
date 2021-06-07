<?php

use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<h3>Изменение записи</h3>

<div>
<?php
    $list = ArrayHelper::map($categories, 'id', 'name');

    $form = ActiveForm::begin(['options' => ['class' => 'form form-horizontal', 'id' => 'ChargeForm']]);
    echo $form->field($model, 'name')->textInput()->label('Название');
    echo $form->field($model, 'description')->textarea()->label('Описание');
    echo $form->field($model, 'amount')->textInput()->label('Сумма');
    echo $form->field($model, 'date')->textInput()->label('Дата');
    echo $form->field($model, 'category_id')->dropDownList($list, ['selected' => $model->category_id])->label('Категория');
    echo HTML::submitButton('Изменить', ['class' => 'btn btn-success']);
    ActiveForm::end();
    ?>
</div>
