<?php


namespace app\controllers;


use app\models\Charge;
use app\models\ChargeForm;
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

    public function actionChargeUpdate(int $id) {
        $model = new ChargeForm();
        $charge = Charge::findOne(['id' => $id]);
        if (is_null($charge) || !$charge->belongsThisUser()) {
            Yii::$app->session->setFlash('error', 'Ошибка! Данной категории не существует!');
            return $this->redirect(['charge/index']);
        }

        if ($model->load(Yii::$app->request->post(), 'ChargeForm')) {
            $model->id = $id;
            if ($model->validate()) {
                $model->save();
                return $this->redirect(['charge/index']);
            }
        }

        $model->setAttributes($charge->attributes);

        $user = Yii::$app->user->identity;  // categories for dropList
        $categories = $user->categories;

        $category = $charge->category; // set category
        $model->category_id = $category->id;
        return $this->render('charge_update', ['model' => $model, 'categories' => $categories]);
    }

    public function actionChargeDelete(int $id) {
        try {
            $charge = Charge::findOne(['id' => $id]);
            if ($charge->belongsThisUser()) {
                $charge->delete();
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка! Данной категории не существует!');
            }
        } catch (\Throwable $e) {
            Yii::$app->session->setFlash('error', 'Неизвестная ошибка!');
            // Логгирование
        }
        finally {
            return $this->redirect(['charge/index']);
        }
    }
}