<?php


namespace app\controllers;

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

    public function actionCategoryUpdate() {

    }

    public function actionCategoryDelete() {
        $category_id = Yii::$app->request->get('id');
        if (!is_numeric($category_id)) {
            Yii::$app->session->setFlash('error', 'Ошибка! Категория с таким id не найдена.');

            $this->goHome();
        } // TODO: Доделать проверку, принадлежит ли эта категория пользователю и вывод ошибки
        $category =
        $user = Yii::$app->user->identity;

    }
}