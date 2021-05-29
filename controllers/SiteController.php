<?php

namespace app\controllers;

use app\models\LoginForm;
use app\models\SignupForm;
use app\models\User;
use Yii;
use yii\web\Controller;

class SiteController extends Controller {

	public function actionIndex() {
//	    var_dump(Yii::$app->user->identity);
//	    die();
        if (!Yii::$app->user->isGuest){
            return $this->redirect(['app/index']);
        }
		return $this->render('index');
	}

	public function actionSignup() {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['app/index']);
        }
		$model = new SignupForm();
        //Registration
		if ($model->load(Yii::$app->request->post())) {
			if ($model->validate()) {
				$model->save();
                return $this->redirect(['app/index']);
            }
		}
		return $this->render('signup', ['model' => $model]);
	}

	public function actionLogin() {
	    if (!Yii::$app->user->isGuest) {
            return $this->redirect(['app/index']);
        }
		$model = new LoginForm();
		//Validation
		if (Yii::$app->request->post('LoginForm')) {
            $model->attributes = Yii::$app->request->post('LoginForm');
            if ($model->validate()) {
                Yii::$app->user->login(User::findUser($model->username));
                return $this->redirect(['app/index']);
            }
        }
		return $this->render('login', ['model' => $model]);
	}

	public function actionLogout() {
	    if (!Yii::$app->user->isGuest) {
            Yii::$app->user->logout();
            return $this->goHome();
        }
    }
}
