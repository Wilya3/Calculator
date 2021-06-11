<?php


namespace app\controllers;

use app\models\Category;
use app\models\CategoryForm;
use app\models\Charge;
use app\models\UserCategory;
use Throwable;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;


class CategoryController extends Controller {

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
        ];  // TODO: APEX js      service entity repository
    }  // If any error occurred, redirect to site/index. If logged, redirect to graph/index

    /**
     * Find all categories which belong this user and charges sum for each category. Add 'sum' column into category
     * @return string  //TODO: Перенести вычисление суммы в жс
     */
    public function actionIndex(): string {
        // Get categories
        $user = Yii::$app->user->identity;
        $categories = $user->categoriesAsArray;
        foreach ($categories as &$category) {
            $user_category = UserCategory::find()
                ->where(['category_id' => $category['id'], 'user_id' => $user->id])->asArray()->one();
            $category['sum'] = Charge::sumOfChargesByCategory($user_category['id']);
        }
        unset($category);

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('index', ['categories' => $categories]);
        }
        return $this->render('index', ['categories' => $categories]);
    }

    /**
     * Add a new category, validate, save.
     * Unique name validator is used.
     * @return string|Response
     */
    public function actionCategoryAdd() {
        $model = new CategoryForm();
        if ($model->load(Yii::$app->request->post(), 'CategoryForm')) {
            if ($model->validate()) {
                $model->save();
                return $this->goBack();
            }
        }
        return $this->render('category_add', ['model' => $model]);
    }

    /**
     * Sets up the POST values into the category with $id.
     * Only a custom category owned by current user can be changed.
     * Else flash message is saved into the session, nothing is changed
     * @param int $id category id to update
     * @return string|Response redirect home
     */
    public function actionCategoryUpdate(int $id) {  // TODO: Refactor
        $category = Category::findOne(['id' => $id]);
        if (is_null($category) || !$category->belongsThisUser() || $category->is_default == 1) {
            Yii::$app->session->setFlash('error',
                'Ошибка! Данной категории не существует или ее нельзя изменять');
            return $this->goBack();
        }
        $model = new CategoryForm();
        if ($model->load(Yii::$app->request->post(), 'CategoryForm')) {
            $model->id = $id;
            if ($model->validate()) {
                $model->save();
                return $this->goBack();
            }
        }
        $model->setAttributes($category->attributes);
        return $this->render('category_update', ['model' => $model]);
    }

    /**
     * Deletes the relation between this (authorized) user and category with id received by GET-request.
     * If the category belongs to the user, it will be deleted from 'category' table.
     * If error has been caught, set flash message into session.
     * @param int $id category id to delete
     * @return Response redirect home
     */
    public function actionCategoryDelete(int $id): Response {
        try {
            $category = Category::findOne(['id' => $id]);
            if (!is_null($category) && $category->belongsThisUser()) {

                // delete relation from junction table. Need for default categories
                $category->unlink('users', Yii::$app->user->identity, true);

                if ($category->is_default == 0) { // if category was created by user, delete it from table Category
                    $category->delete();
                }
            }
        } catch (Throwable $e) {
            Yii::$app->session->setFlash('error', 'Ошибка удаления!');
        } finally {
            return $this->goBack();
        }
    }
}