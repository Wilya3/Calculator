<?php


namespace app\controllers;

use app\models\Category;
use app\models\CategoryForm;
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
    }  // If any error occurred, redirect to site/index. If logged, redirect to category/index

    public function actionIndex() {
        // Get categories
        $user = Yii::$app->user->identity;
        $table = $user->categories;  // (ActiveRecord) User->getCategories()->hasMany()
        return $this->render('index', ['table' => $table]);
    }

    /**
     * Add a new category, validate, save.
     * Unique name validator is used.
     */
    public function actionCategoryAdd() {
        $model = new CategoryForm();
//        $model->scenario = CategoryForm::SCENARIO_ADD;  // required for validation
        if ($model->load(Yii::$app->request->post(), 'CategoryForm')) {
            if ($model->validate()) {
                $model->save();
                return $this->goHome();
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
            return $this->goHome();
        }
        $model = new CategoryForm();
        if ($model->load(Yii::$app->request->post(), 'CategoryForm')) {
            $model->id = $id;
            if ($model->validate()) {
                $model->save();
                return $this->goHome();
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
            Yii::$app->session->setFlash('error', 'Ошибка! Данной категории не существует.');
        } finally {
            return $this->goHome();
        }
    }
}