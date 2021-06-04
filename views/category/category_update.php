<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<h3>Изменение категории</h3>
<div>
    <?php
    $form = ActiveForm::begin(['options' => ['class'=>'form form-horizontal', 'id'=>'CategoryForm']]);
    echo $form->field($model, 'name')->label("Название");
    echo $form->field($model, 'description')->textarea()->label("Описание");
    echo Html::submitButton('Изменить', ['class'=>'btn btn-success']);
    ActiveForm::end();
    ?>
</div>
