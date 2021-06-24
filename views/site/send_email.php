<?php
/**
 * @var $model SendEmailForm
 */

use app\models\SendEmailForm;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div>
    <?php
    $form = ActiveForm::begin([
        'options' => [
            'id'=>'SendEmailForm',
            'class'=>'form form-horizontal col-md-4',
        ]
    ]);

    echo $form->field($model, 'email')->label("Email");
    echo Html::submitButton('Отправить', ['class'=>'btn btn-success']);
    ActiveForm::end();
    ?>
</div>
