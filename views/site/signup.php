<?php
use yii\widgets\ActiveForm;
use yii\helpers\HTML;
?>
<h1>Регистрация</h1>
<a href="/web/index.php?r=site%2Flogin">Вход</a>

<div>
	<?php
	$form = ActiveForm::begin(['options' => ['class'=>'form-horizontal', 'id'=>'signupForm']]);
	echo $form->field($model, 'username');
	echo $form->field($model, 'email');
	echo $form->field($model, 'password')->passwordInput();
	echo Html::submitButton('Зарегистрироваться', ['class'=>'btn btn-success']);
	ActiveForm::end();
	?>
</div>

