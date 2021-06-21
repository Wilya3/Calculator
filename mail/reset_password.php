<?php
/**
 * @var $user app\models\User
 */
?>
<h3>Здравствуйте, <?=$user->username?>!</h3>
Поступил запрос на смену пароля для Вашего аккаунта. Если это не Вы, то просто проигнорируйте это сообщение.

Для сброса пароля перейдите по следующей ссылке:
<?= Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'key' => $user->secret_key])?>
