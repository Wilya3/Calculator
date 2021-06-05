<?php


namespace app\controllers;

use Yii;
use yii\web\Controller;

class ChargeController extends Controller {
    // TODO: спросить насчет запросов yii2: ...WHERE...IN (...), когда значений будет много
    public function actionIndex() {
        $user = Yii::$app->user->identity;
        $table = $user->charges;
        return $this->render('index', ['table' => $table]);
    }
}