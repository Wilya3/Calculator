<?php


namespace app\controllers;


use Yii;
use yii\base\InvalidArgumentException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class ErrorController extends Controller {
    /**
     * Sets flash message and redirects to the home.
     * If error is important, logs it.
     * @return Response
     */
    public function actionIndex(): Response {
        $exception = Yii::$app->errorHandler->exception;
        if (is_null($exception)) return $this->goHome();

        // User exceptions
        if ($exception instanceof BadRequestHttpException || $exception instanceof NotFoundHttpException) {
            Yii::$app->session->setFlash('error', "Ошибка! Неверный адрес!");
        } elseif ($exception instanceof ForbiddenHttpException) {
            Yii::$app->session->setFlash('error', "Ошибка! У Вас нет доступа!");
        } elseif ($exception instanceof InvalidEmailException) {
            Yii::$app->session->setFlash('error', "Ошибка! Неверный Email!");
        }

        // Fatal error
//        elseif ($exception instanceof InvalidArgumentException) {
//            Yii::warning($exception->getMessage() . $exception->getTraceAsString());
//            Yii::$app->session->setFlash('error', "Ошибка! Получены неверные данные");
//        }
        elseif ($exception instanceof yii\db\Exception) {
            Yii::error($exception->getMessage() . $exception->getTraceAsString());
            Yii::$app->session->setFlash('error', "Критическая ошибка работы сервера! Мы уже работаем над этим!");
        } else {
            Yii::error($exception->getMessage() . $exception->getTraceAsString());
            Yii::$app->session->setFlash('error', "Неизвестная ошибка!");
        }
        return $this->goHome();
    }
}
