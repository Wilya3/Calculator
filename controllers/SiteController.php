<?php

namespace app\controllers;

use app\models\LoginForm;
use app\models\ResetPasswordForm;
use app\models\SendEmailForm;
use app\models\SignupForm;
use app\models\User;
use PharIo\Manifest\InvalidEmailException;
use Yii;
use yii\base\Exception;
use yii\base\InvalidArgumentException;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class SiteController extends Controller {

    public function behaviors(): array {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['login', 'logout', 'signup'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['login', 'signup', 'send-email', 'reset-password'],
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
    // TODO: Сделать тесты. Добавить "авторов" :)

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

    /**
     * @throws InvalidEmailException If email is not found
     * @throws Exception If secret_key generating error
     */
    public function actionSendEmail() {
        $model = new SendEmailForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->sendEmail()) {
                    Yii::$app->session->setFlash('success',
                        "Проверьте почту. На нее отправлено письмо с дальнейшими инструкциями");
                    return $this->refresh();
                } else {
                    throw new InvalidEmailException;
                }
            }
        }
        return $this->render('send_email', ['model' => $model]);
    }

    /**
     * @param string $key secret_key, which is used for validation before password reset
     * @return string|Response go login if success
     * @throws NotFoundHttpException if key is null
     */
    public function actionResetPassword(string $key) {
        if (is_null($key)) {
            throw new NotFoundHttpException();
        }
        $model = new ResetPasswordForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $model->resetPassword($key);
                Yii::$app->session->setFlash('success', "Пароль успешно изменен");
                return $this->redirect(['site/login']);
            }
        }
        return $this->render('reset_password', ['model' => $model]);
    }
}
