<?php
/**
 * @var $model app\models\SignupForm
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\HTML;
?>
<h1>Регистрация</h1>

<div>
	<?php
    $form = ActiveForm::begin([
        'options' => [
            'id'=>'signupForm',
            'class'=>'form form-horizontal col-md-8',
        ]
    ]);
	echo $form->field($model, 'username')->label("Логин");
	echo $form->field($model, 'email')->label("E-mail");
	echo $form->field($model, 'password')->passwordInput()->label("Пароль");
	echo Html::submitButton('Зарегистрироваться', ['class'=>'btn btn-success']);
    echo "  /  ";
    echo Html::a('Вход', 'login');
	ActiveForm::end();
	?>
</div>

