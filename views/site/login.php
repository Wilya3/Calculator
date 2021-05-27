<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<h1>Вход</h1>

<div>
	<?php
	$form = ActiveForm::begin(['options' => ['class'=>'form form-horizontal', 'id'=>'loginForm']]);
	echo $form->field($model, 'username')->label("Логин");
	echo $form->field($model, 'password')->passwordInput()->label("Пароль");
//    echo $form->errorSummary($model);
	echo Html::submitButton('Войти', ['class'=>'btn btn-success']);
	ActiveForm::end();
	?>
    <a href="/web/index.php?r=site%2Fsignup">Регистрация</a>
</div>
