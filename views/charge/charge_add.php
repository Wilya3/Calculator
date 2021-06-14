<?php

use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
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
    echo $form->field($model, 'date')->label('Дата')->widget(DatePicker::class, [
        'model' => $model,
        'attribute' => 'date',
        'type' => DatePicker::TYPE_COMPONENT_APPEND,
        'language' => 'ru',
        'pluginOptions' => [
            'autoclose'=>true,
            'format' => 'yyyy-mm-dd',
            'startDate' => "2000-01-01",
            'endDate' => date("Y-m-d"),
        ]
    ]);
    echo $form->field($model, 'category_id')->dropDownList($list)->label('Категория');
    echo HTML::submitButton('Добавить', ['class' => 'btn btn-success']);
    ActiveForm::end();
    ?>
</div>
