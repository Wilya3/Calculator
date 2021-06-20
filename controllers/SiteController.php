<?php

namespace app\controllers;

use app\models\LoginForm;
use app\models\SignupForm;
use app\models\User;
use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

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
    }  // If any error occurred, redirect to site/index. If logged, redirect to graph/index
    // TODO: Сделать "забыли пароль", тесты. Добавить "авторов" :)

	public function actionIndex() {
        if (!Yii::$app->user->isGuest){
            return $this->redirect(['graph/index']);
        }
		return $this->render('index');
	}


    /**
     * @throws Exception If couldn't generate random string for authKey
     */
    public function actionSignup() {
        // Registration
		$model = new SignupForm();
		if ($model->load(Yii::$app->request->post())) {
			if ($model->validate()) {
				$model->save();
                Yii::$app->user->login(User::findOne(['username' => $model->username]));
                return $this->redirect(['graph/index']);
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
                Yii::$app->user->login(User::findOne(['username' => $model->username]), $model->rememberMe ? 3600*24*30 : 0);
                return $this->redirect(['graph/index']);
            }
        }
		return $this->render('login', ['model' => $model]);
	}

	public function actionLogout(): Response {
        Yii::$app->user->logout(true);
        return $this->goHome();
    }
}
