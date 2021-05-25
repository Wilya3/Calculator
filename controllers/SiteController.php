<?php

namespace app\controllers;

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
}