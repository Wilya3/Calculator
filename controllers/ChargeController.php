<?php


namespace app\controllers;


use app\models\ChargeForm;
use app\models\Test;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class ChargeController extends Controller {

    public function behaviors(): array {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@']
                    ],
                ],
            ],
        ];
    }  // If any error occurred, redirect to site/index. If logged, redirect to category/index


    // TODO: спросить насчет запросов yii2: ...WHERE...IN (...), когда значений будет много
    public function actionIndex() {
        $user = Yii::$app->user->identity;
        $table = $user->charges;
        return $this->render('index', ['table' => $table]);
    }

    public function actionChargeAdd() {
        $model = new ChargeForm();
        if ($model->load(Yii::$app->request->post(), 'ChargeForm')) {
            if ($model->validate()) {
                $model->save();
                $this->redirect(['charge/index']);
            }
        }
        $user = Yii::$app->user->identity;
        $categories = $user->categories;
        return $this->render('charge_add', ['model' => $model, 'categories' => $categories]);
    }
}