<?php

namespace app\controllers;

use app\models\LoginForm;
use app\models\SignupForm;
use Yii;
use yii\web\Controller;

class SiteController extends Controller {

	public function actionIndex() {
		return $this->render('index');
	}

	public function actionSignup() {
		$model = new SignupForm();
		if ($model->load(Yii::$app->request->post())) {
			if ($model->validate()) {
				$model->save();
			}
		}
		return $this->render('signup', ['model' => $model]);
	}

	public function actionLogin() {
		$model = new LoginForm();
		if (Yii::$app->request->post('LoginForm')) {
            $model->attributes = Yii::$app->request->post('LoginForm');
//            var_dump($model);
//            die();
            if ($model->validate()) {
//                var_dump("Валидация пройдена");
//                die();
            }
        }
		return $this->render('login', ['model' => $model]);
	}
}
