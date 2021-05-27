<?php


namespace app\models;

use \yii\base\Model;


class LoginForm extends Model {

	public $username;
	public $password;

	public function rules() {
		return [
			[['username', 'password'], 'required'],
            ['password', 'validatePassword']
		];
	}

	/**
	 * Find user with username from DB. Compare password hash from model and password hash from DB.
	 * @param string $attribute Field from model, which will show an error.
	 */
	public function validatePassword($attribute) {
	    $user = User::getUserByUsername($this->username);
	    if (is_null($user) || $user["password"] != password_hash($this->password, PASSWORD_BCRYPT)) {
	        $this->addError($attribute, "Логин или пароль введены неверно.");
        }
//	    print_r($user);
//	    die();
    }
}