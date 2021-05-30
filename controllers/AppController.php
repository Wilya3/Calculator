<?php


namespace app\controllers;

use app\models\Category;
use Yii;
use yii\web\Controller;


class AppController extends Controller {

    public function actionIndex() {
        if (Yii::$app->user->isGuest)
            $this->redirect(['site/index']);
        $user = Yii::$app->user->identity;
        $table = $user->categories;
        return $this->render('index', ['table' => $table]);
    }

//    public function actionCategoryUpdate() {
//
//    }
//
//    public function actionCategoryDelete() {
//        Yii
//    }
}