<?php
/**
 * @var $model app\models\CategoryForm
 */
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin(['options' => ['class' => 'form form-horizontal', 'id' => 'CategoryForm']]);
echo $form->field($model, 'name')->label("Название");
echo $form->field($model, 'description')->textarea()->label("Описание");
echo Html::submitButton('Изменить', ['class' => 'btn btn-success']);
ActiveForm::end();

