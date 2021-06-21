<?php
/**
 * @var $categories app\models\Category
 * @var $model app\models\ChargeForm
 */
use kartik\date\DatePicker;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;


$list = ArrayHelper::map($categories, 'id', 'name');

$form = ActiveForm::begin(['options' => ['class' => 'form form-horizontal', 'id' => 'ChargeForm']]);
echo $form->field($model, 'name')->textInput()->label('Название');
echo $form->field($model, 'description')->textarea()->label('Описание');
echo $form->field($model, 'amount')->textInput()->label('Сумма');
echo $form->field($model, 'date')->label('Дата')->widget(DatePicker::class, [
    'model' => $model,
    'attribute' => 'date',
    'value' => date("Y-m-d"),
    'type' => DatePicker::TYPE_COMPONENT_APPEND,
    'language' => 'ru',
    'pluginOptions' => [
        'autoclose'=>true,
        'format' => 'yyyy-mm-dd',
        'startDate' => "2000-01-01",
        'endDate' => date("Y-m-d"),
    ]
]);
echo $form->field($model, 'category_id')->dropDownList($list,
    (is_null($model->category_id) ?: ['selected' => $model->category_id]))->label('Категория');
echo HTML::submitButton('Сохранить', ['class' => 'btn btn-success']);
ActiveForm::end();
