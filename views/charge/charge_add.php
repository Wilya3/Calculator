<?php

use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<h3>Добавление записи</h3>

<div>
<?php
    $list = ArrayHelper::map($categories, 'id', 'name');

    $form = ActiveForm::begin(['options' => ['class' => 'form form-horizontal', 'id' => 'ChargeForm']]);
    echo $form->field($model, 'name')->textInput()->label('Название');
    echo $form->field($model, 'description')->textarea()->label('Описание');
    echo $form->field($model, 'amount')->textInput()->label('Сумма');
    echo $form->field($model, 'date')->textInput()->label('Дата');
    echo $form->field($model, 'category_id')->dropDownList($list)->label('Категория');
    echo HTML::submitButton('Добавить', ['class' => 'btn btn-success']);
    ActiveForm::end();
    ?>
</div>
