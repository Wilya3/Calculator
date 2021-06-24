<?php


namespace app\controllers;


use app\models\Category;
use app\models\Charge;
use app\models\ChargeForm;
use Throwable;
use Yii;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
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

    /**
     * Renders all charges that belong this user.
     * Adds two new columns to each charge: category_id and category_name.
     * Charges are returned as array.
     * @return string
     */
    public function actionIndex(): string {
        $user_id = Yii::$app->user->getId();
        $charges = Charge::find()
            ->select(['charge.*', 'category.id AS category_id', 'category.name AS category_name'])
            ->join('INNER JOIN', 'user_category', '`charge`.`user_category_id` = `user_category`.`id`')
            ->join('INNER JOIN', 'category', '`user_category`.`category_id` = `category`.`id`')
            ->where(['user_category.user_id' => $user_id])
            ->asArray()
            ->all();
        return $this->render('charge_grid_view', ['charges' => $charges]);
    }

    /**
     * Renders charges only for one category, which id is received. Sends charges as array.
     * Before all, checks is this category belongs this user.
     * @param int $id id of the category, to which the charges are linked
     * @return string charge_by_category view
     * @throws BadRequestHttpException If category is not found or has not any relation with user
     */
    public function actionChargesByCategory(int $id): string {
        $category = Category::findOne(['id' => $id]);
        if (is_null($category) || !$category->belongsThisUser()) {
            throw new BadRequestHttpException();
        }
        $user_id = Yii::$app->user->getId();
        $charges = Charge::find()  // all charges for this category, which belongs this user
            ->join('INNER JOIN', 'user_category', '`user_category_id` = `user_category`.`id`')
            ->where("`user_category`.`user_id` = $user_id")
            ->andWhere("`user_category`.`category_id` = $id")
            ->asArray()
            ->all();
        return $this->render('charge_by_category', ['charges' => $charges, 'category' => $category]);
    }

    /**
     * Renders empty form with categories for choice.
     * If POST - adds a new charge, validate, save.
     * @param int|null $category_id id of the category, which will be default value for category choice.
     * @return string|Response If success, redirect to charge/charges-by-category
     */
    public function actionChargeAdd(int $category_id = null) {
        $model = new ChargeForm();
        // if post - charge add
        if ($model->load(Yii::$app->request->post(), 'ChargeForm')) {
            if ($model->validate()) {
                $model->save();
                return $this->redirect(["charge/charges-by-category?id=$model->category_id"]);
            }
        }
        // set current category (it could be used in category choice)
        if (!is_null($category_id)) {
            $model->category_id = $category_id;
        }
        // collect categories for category choice
        $user = Yii::$app->user->identity;
        $categories = $user->categoriesAsArray;
        return $this->render('charge_add', ['model' => $model, 'categories' => $categories]);
    }

    /**
     * Renders form with current charge.
     * If POST - validates (also checks is the category belongs this user), then saves.
     * @param int $id Charge id
     * @return string|Response If success, redirects to charge/charges-by-category
     * @throws BadRequestHttpException If charge is not found or has not any relation with user
     */
    public function actionChargeUpdate(int $id) {
        $model = new ChargeForm();
        // charge validate
        $charge = Charge::findOne(['id' => $id]);
        if (is_null($charge) || !$charge->belongsThisUser()) {
            throw new BadRequestHttpException();
        }
        // if post - charge update
        if ($model->load(Yii::$app->request->post(), 'ChargeForm')) {
            $model->id = $id;
            if ($model->validate()) {
                $model->save();
                return $this->redirect(["charge/charges-by-category?id=$model->category_id"]);
            }
        }
        // collect current data for view
        $model->setAttributes($charge->attributes);
        $category = $charge->category; // set current category for category choice
        $model->category_id = $category->id;

        $user = Yii::$app->user->identity;  // collect all categories for category choice
        $categories = $user->categoriesAsArray;
        return $this->render('charge_update', ['model' => $model, 'categories' => $categories]);
    }

    /**
     * Deletes category. Before deleting validates, is this charge belongs this user.
     * @param int $id Charge id
     * @return Response Redirect to referrer
     * @throws BadRequestHttpException If charge is not found or has not any relation with user
     * @throws StaleObjectException if [[optimisticLock|optimistic locking]] is enabled
     * and the data being deleted is outdated.
     * @throws Throwable
     */
    public function actionChargeDelete(int $id): Response {
        $charge = Charge::findOne(['id' => $id]);
        if (is_null($charge) || !$charge->belongsThisUser()) {
            throw new BadRequestHttpException();
        }
        $charge->delete();
        return $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
    }
}