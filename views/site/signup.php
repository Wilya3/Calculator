<?php
use yii\widgets\ActiveForm;
use yii\helpers\HTML;
?>
<h1>Регистрация</h1>

<div>
	<?php
	$form = ActiveForm::begin(['options' => ['class'=>'form-horizontal', 'id'=>'signupForm']]);
	echo $form->field($model, 'username')->label("Логин");
	echo $form->field($model, 'email')->label("E-mail");
	echo $form->field($model, 'password')->passwordInput()->label("Пароль");
	echo Html::submitButton('Зарегистрироваться', ['class'=>'btn btn-success']);
	ActiveForm::end();
	?>
    <a href="/web/index.php?r=site%2Flogin">Вход</a>
</div>

