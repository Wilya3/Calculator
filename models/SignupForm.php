<?php


namespace app\models;


use yii\base\Model;

/**
 * Class SignupForm works with 'user' table
 * @package app\models
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
            [['username'], 'string', 'max' => 30],
            [['email'], 'string', 'max' => 255],
            [['username','email'],'filter','filter'=>'\yii\helpers\HtmlPurifier::process'] //css, xss
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