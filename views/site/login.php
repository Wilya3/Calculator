<?php
/**
 * @var $model app\models\LoginForm
 */
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
?>

<h1>Вход</h1>

<div>
	<?php
	$form = ActiveForm::begin([
	        'options' => [
                'id'=>'LoginForm',
                'class'=>'form form-horizontal col-md-8',
            ]
    ]);
	echo $form->field($model, 'username')->label("Логин");
	echo $form->field($model, 'password')->passwordInput()->label("Пароль");
	echo $form->field($model, 'rememberMe')->checkbox()->label("Запомнить меня");
	echo Html::submitButton('Войти', ['class'=>'btn btn-success']);
    echo "  /  ";
	echo Html::a('Регистрация', 'signup');
    echo "  /  ";
	echo Html::a('Забыли пароль?', 'send-email');
	ActiveForm::end();
	?>
</div>
