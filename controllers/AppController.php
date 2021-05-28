<?php


namespace app\controllers;

use app\models\Category;
use Yii;
use yii\web\Controller;


class AppController extends Controller {

    public function actionIndex() {
        $table = Category::findCategories(Yii::$app->user->getId());
        return $this->render('index', ['table' => $table]);
    }
}