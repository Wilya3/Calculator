<?php


namespace app\models;


use yii\base\Model;

/*
 * Form for table 'user' from DB 'calculator'
 */
class SignupForm extends Model {

	public $username;
	public $password;
	public $email;

	public function rules() {
		return [
			[['username', 'password', 'email'], 'required'],
			[['username', 'email'], 'unique', 'targetClass' => 'app\models\User'],
			['email', 'email'],
		];
	}

	public function save() {
		$user = new User();
		$user->username = $this->username;
		$user->password = password_hash($this->password, PASSWORD_BCRYPT);
		$user->email = $this->email;
		$user->save();
	}
}