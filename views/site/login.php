<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
?>

<h1>Вход</h1>
<a href="/web/index.php?r=site%2Fsignup">Регистрация</a>

<div>
	<?php
	$form = ActiveForm::begin(['options' => ['class'=>'form form-horizontal', 'id'=>'loginForm']]);
	echo $form->field($model, 'username');
	echo $form->field($model, 'password')->passwordInput();
	echo Html::submitButton('Войти', ['class'=>'btn btn-success']);
	ActiveForm::end();
	?>
</div>
