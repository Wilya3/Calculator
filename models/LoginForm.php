<?php


namespace app\models;

use \yii\base\Model;


/**
 * Class LoginForm works with 'user' table.
 * @package app\models
 */
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
	 * Find user with username from DB. Compare password hash from model and from DB.
	 * @param string $attribute Field from model, which will show an error.
	 */
	public function validatePassword($attribute) {
	    $user = User::findUser($this->username);
	    if (is_null($user) || !password_verify($this->password, $user->password)) {
	        $this->addError($attribute, "Логин или пароль введены неверно.");
        }
    }
}