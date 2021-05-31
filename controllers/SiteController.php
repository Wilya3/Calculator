<?php

namespace app\controllers;

use app\models\LoginForm;
use app\models\SignupForm;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class SiteController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['login', 'logout', 'signup'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login', 'signup'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['logout'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }  // If any error occurred, redirect to site/index. If logged, redirect to app/index

    public function actionError() {
        return $this->redirect(['site/index']);
    }

	public function actionIndex() {
        if (!Yii::$app->user->isGuest){
            return $this->redirect(['app/index']);
        }
		return $this->render('index');
	}

	public function actionSignup() {
        // Registration
		$model = new SignupForm();
		if ($model->load(Yii::$app->request->post())) {
			if ($model->validate()) {
				$model->save();
                Yii::$app->user->login(User::findUser($model->username), 3600*24*30);
                return $this->redirect(['app/index']);
            }
		}
		return $this->render('signup', ['model' => $model]);
	}

	public function actionLogin() {
        // Validation
		$model = new LoginForm();
		if (Yii::$app->request->post('LoginForm')) {
            $model->attributes = Yii::$app->request->post('LoginForm');
            if ($model->validate()) {
                Yii::$app->user->login(User::findUser($model->username), 3600*24*30);
                return $this->redirect(['app/index']);
            }
        }
		return $this->render('login', ['model' => $model]);
	}

	public function actionLogout() {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}
