<?php


namespace app\controllers;


use app\models\Category;
use app\models\Charge;
use app\models\ChargeForm;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;

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
    //TODO: Добавить "авторов" :)

    /**
     * Renders all charges as array. Adds two new columns to each charge: category_id and category_name
     * @return string
     */
    public function actionIndex() {
        $user_id = Yii::$app->user->getId();
        $charges = Charge::find()
            ->select(['charge.*', 'category.id AS category_id', 'category.name AS category_name'])
            ->join('INNER JOIN', 'user_category', '`charge`.`user_category_id` = `user_category`.`id`')
            ->join('INNER JOIN', 'category', '`user_category`.`category_id` = `category`.`id`')
            ->where(['user_category.user_id' => $user_id])
            ->asArray()
            ->all();
        return $this->render('index', ['charges' => $charges]);
    }

    /**
     * Renders charges only for one category, which id is received. Sends charges as array.
     * Before all, checks is this category belongs this user.
     * If error occurred, sets flash message into the session
     * @param int $id id of the category, to which the charges are linked
     * @return string
     */
    public function actionChargesByCategory(int $id=null): string {
        $category = Category::findOne(['id' => $id]);
        if (!$category->belongsThisUser()) {
            Yii::$app->session->setFlash('error', 'Ошибка! Данной записи не существует!');
        }
        $charges = $category->chargesAsArray;
        return $this->render('index', ['charges' => $charges, 'category' => $category]);
    }

    /**
     * Adds a new charge, validate, save.
     * If GET - renders empty form with filled dropList of categories
     * @param int|null $category_id id of the category, which will be the default value of dropList
     * @return string|Response If success, redirect to charge/charges-by-category
     */
    public function actionChargeAdd(int $category_id = null) {
        $model = new ChargeForm();
        if ($model->load(Yii::$app->request->post(), 'ChargeForm')) {
            if ($model->validate()) {
                $model->save();
                return $this->redirect(["charge/charges-by-category?id={$model->category_id}"]);
            }
        }
        // collect categories for dropList
        if (!is_null($category_id)) {
            $model->category_id = $category_id;
        }
        $user = Yii::$app->user->identity;
        $categories = $user->categories;
        return $this->render('charge_add', ['model' => $model, 'categories' => $categories]);
    }

    /**
     * Renders form with current attribute of charge.
     * If POST, validates (also checks is the category belongs this user), then saves.
     * If error occurred, sets flash message into the session
     * @param int $id Charge id
     * @return string|Response If success, redirects to charge/charges-by-category
     */
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
                return $this->redirect(["charge/charges-by-category?id={$model->category_id}"]);
            }
        }
        // collect current data for view
        $model->setAttributes($charge->attributes);
        $user = Yii::$app->user->identity;  // categories for dropList
        $categories = $user->categories;
        $category = $charge->category;  // set current category for dropList
        $model->category_id = $category->id;  //TODO: Оптимизировать с массивами
        return $this->render('charge_update', ['model' => $model, 'categories' => $categories]);
    }

    /**
     * Deletes category. Before deleting validates, is this charge belongs this user.
     * If error occurred, sets flash message into the session
     * @param int $id Charge id
     * @return Response Redirect to referrer
     */
    public function actionChargeDelete(int $id) {
        try {
            $charge = Charge::findOne(['id' => $id]);
            if ($charge->belongsThisUser()) {
                $charge->delete();
            }
        } catch (\Throwable $e) {
            Yii::$app->session->setFlash('error', 'Ошибка удаления!');
        }
        finally {
            return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
        }
    }
}