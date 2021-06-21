<?php


namespace app\models;


use yii\base\Model;
use yii\web\NotFoundHttpException;

class ResetPasswordForm extends Model {
    public $password;
    public $repeatPassword;

    public function rules(): array {
        return [
            [['password', 'repeatPassword'], 'required'],
            ['repeatPassword', 'compare', 'compareAttribute' => 'password'],
        ];
    }

    /**
     * @param string $key secret_key, which is used for validation before password reset
     * @throws NotFoundHttpException If there is no any user with received key
     */
    public function resetPassword(string $key) {
        $user = User::findOne(['secret_key' => $key]);
        if (is_null($user)) {
            throw new NotFoundHttpException();
        }
        $user->secret_key = null;
        $user->password = password_hash($this->password, PASSWORD_BCRYPT);
        $user->save();
    }

}