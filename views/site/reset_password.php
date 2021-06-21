<?php
/**
 * @var $model ResetPasswordForm
 */

use app\models\ResetPasswordForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>
<div>
    <?php

    $form = ActiveForm::begin([
        'options' => [
            'id'=>'ResetPasswordForm',
            'class'=>'form form-horizontal col-md-4',
        ]
    ]);

    echo $form->field($model, 'password')->passwordInput()->label("Новый пароль");
    echo $form->field($model, 'repeatPassword')->passwordInput()->label("Повторите новый пароль");
    echo Html::submitButton('Изменить', ['class'=>'btn btn-success']);
    ActiveForm::end();
    ?>
</div>