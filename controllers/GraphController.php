<?php


namespace app\controllers;


use Yii;
use yii\web\Controller;

class GraphController extends Controller {

    public function actionIndex(): string {
        $user = Yii::$app->user->identity;
        $charges = $user->charges;
        $categories = $user->categories;
        return $this->render('index', ['charges' => $charges, 'categories' => $categories]);
    }
}