<?php


namespace app\models;

use yii\base\Model;


/**
 * Class LoginForm works with 'user' table.
 * @package app\models
 */
class LoginForm extends Model {

	public $username;
	public $password;
	public $rememberMe;

	public function rules() {
		return [
			[['username', 'password'], 'required'],
            ['password', 'validatePassword'],
            ['rememberMe', 'boolean']
		];
	}

	/**
	 * Find user with username from DB. Compare password hash from model and from DB.
	 * @param string $attribute Field from model, which will show an error.
	 */
	public function validatePassword(string $attribute) {
	    $user = User::findOne(['username' => $this->username]);
	    if (is_null($user) || !password_verify($this->password, $user->password)) {
	        $this->addError($attribute, "Логин или пароль введены неверно.");
        }
    }
}