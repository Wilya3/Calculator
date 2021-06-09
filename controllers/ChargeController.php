<?php


namespace app\controllers;


use app\models\Category;
use app\models\Charge;
use app\models\ChargeForm;
use app\models\UserCategory;
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
    }  // If any error occurred, redirect to site/index. If logged, redirect to graph/index


    // TODO: Что делать с дублирующимся кодом ниже?
    public function actionIndex() {
//        $user = Yii::$app->user->identity;  //Новое: 6 запросов к бд, 60мс,  2.0с загрузка
//        $charges = $user->charges;          //Старое: 11 запросов к бд, 110мс, 2.1с загрузка
//        return $this->render('index_old', ['charges' => $charges]);
        $user_id = Yii::$app->user->getId();
        $user_category = UserCategory::find()
            ->where(['user_id' => $user_id])
            ->asArray()
            ->all();
        $categories = Category::find()
            ->where(['in', 'id', array_column($user_category, 'category_id')])
            ->asArray()
            ->all();
        $charges = Charge::find()
            ->where(['in', 'user_category_id', array_column($user_category, 'id')])
            ->asArray()
            ->all();
        return $this->render('index',
            ['charges' => $charges,
             'categories' => $categories,
             'user_category' => $user_category]);
    }

    public function actionChargeAdd() {
        $model = new ChargeForm();
        if ($model->load(Yii::$app->request->post(), 'ChargeForm')) {
            if ($model->validate()) {
                $model->save();
                $this->goHome();
            }
        }
        $user = Yii::$app->user->identity;
        $categories = $user->categories;
        return $this->render('charge_add', ['model' => $model, 'categories' => $categories]);
    }

    public function actionChargeUpdate(int $id) {
        $model = new ChargeForm();
        // charge validate
        $charge = Charge::findOne(['id' => $id]);
        if (is_null($charge) || !$charge->belongsThisUser()) {
            Yii::$app->session->setFlash('error', 'Ошибка! Данной категории не существует!');
            return $this->goHome();
        }
        // charge update
        if ($model->load(Yii::$app->request->post(), 'ChargeForm')) {
            $model->id = $id;
            if ($model->validate()) {
                $model->save();
                return $this->goHome();
            }
        }
        // collect data for view
        $model->setAttributes($charge->attributes);
        $user = Yii::$app->user->identity;  // categories for dropList
        $categories = $user->categories;
        $category = $charge->category;  // set current category for dropList
        $model->category_id = $category->id;  //TODO: Оптимизировать с массивами
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
            return $this->goHome();
        }
    }
}