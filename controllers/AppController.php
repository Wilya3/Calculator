<?php


namespace app\controllers;

use app\models\Category;
use app\models\CategoryForm;
use Throwable;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;


class AppController extends Controller {

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
    }  // If any error occurred, redirect to site/index. If logged, redirect to app/index

    public function actionIndex() {
        $model = new CategoryForm();
        $model->scenario = CategoryForm::SCENARIO_ADD;

        // Убрать этот говнокод и делать редирект не получается. Тогда форма при ошибках пустая

        // Category add
        if (Yii::$app->request->post('CategoryForm')) {
            $this->actionCategoryAdd($model);
        }
        // Get categories
        $user = Yii::$app->user->identity;
        unset($user->categories); // without that category added in this iteration will not be shown
        $table = $user->categories;
        return $this->render('index', ['table' => $table, 'model' => $model]);
    }

    /**
     * It takes an empty model, fill it, validate and save.
     * Then set $model to a new instance of CategoryForm to clean up the attributes.
     * If an error occurs, flash message is saved into the session
     * @param $model CategoryForm empty model
     */
    public function actionCategoryAdd(CategoryForm & $model) {
        $model->attributes = Yii::$app->request->post('CategoryForm');
        if ($model->validate()) {
            $model->save();
            $model = new CategoryForm();
        }
    }

    /**
     * Set POST values to the category with $id.
     * Only a custom category owned by current user can be changed.
     * Else flash message is saved into the session, nothing is changed
     * @param int $id category id to update
     * @return string|Response redirect home
     */
    public function actionCategoryUpdate(int $id) {
        $category = Category::findUsersCategory($id);
        if (is_null($category) || $category->is_default === 1) {
            Yii::$app->session->setFlash('error',
                'Ошибка! Данной категории не существует или ее нельзя изменять');
            return $this->goHome();
        }
        $model = new CategoryForm();
        if (Yii::$app->request->post('CategoryForm')) {
            $model->attributes = Yii::$app->request->post('CategoryForm');
            $model->id = $id;
            if ($model->validate()) {
                $model->save();
                return $this->goHome();
            }
        }
        $model->attributes = $category->attributes;
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
            $category = Category::findUsersCategory($id);
            $user = Yii::$app->user->identity;

            // delete relation in junction table ("delete: true" means deleting row in junction table)
            $category->unlink('users', $user, true);

            if ($category->is_default == 0) { // if category was created by user, delete it from table Category
                $category->delete();
            }
        } catch (Throwable $e) {
            Yii::$app->session->setFlash('error', 'Ошибка! Данной категории не существует.');
        } finally {
            return $this->goHome();
        }
    }
}