<?php


namespace app\models;


use Yii;
use yii\base\Exception;
use yii\base\Model;

class SendEmailForm extends Model {
    public $email;

    public function rules(): array {
        return [
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist', 'targetClass' => 'app\models\User', 'message' => 'Данный Email не найден'],
        ];
    }

    /**
     * @throws Exception If secret_key generating error
     */
    public function sendEmail(): bool {
        $user = User::findOne(['email' => $this->email]);
        if (is_null($user)) {
            return false;
        }
        $user->secret_key = Yii::$app->security->generateRandomString(255);
        if ($user->save()) {
            Yii::$app->mailer->compose('reset_password', ['user' => $user])
                ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->name])
                ->setTo([$user->email => $user->username])
                ->setSubject('Сброс пароля')
                ->send();
        }
        return true;
    }
}