<?php


namespace app\controllers;

use app\models\Category;
use app\models\CategoryForm;
use Yii;
use yii\base\UserException;
use yii\filters\AccessControl;
use yii\web\Controller;


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
        // Category add processing
        $model = new CategoryForm();
        if (Yii::$app->request->post('CategoryForm')) {
            $model->attributes = Yii::$app->request->post('CategoryForm');
            try {
                if ($model->validate()) {
                    $model->save();
                }
            } catch (UserException $e) {
                Yii::$app->session->setFlash('error', 'Категория с таким именем уже существует!');
            }
        }
        // Get categories
        $user = Yii::$app->user->identity;
        unset($user->categories); // without this added category will not be shown
        $table = $user->categories;
        return $this->render('index', ['table' => $table, 'model' => $model]);
    }

    public function actionCategoryUpdate() {

    }

    /**
     * Deletes the relation between this (authorized) user and category with id received by GET-request.
     * If the category belongs to the user, it will be deleted from 'category' table.
     * If error has been caught, set flash message into session.
     */
    public function actionCategoryDelete(int $id) {
        try {
            $category = Category::findOne(['id' => $id]);
            $user = Yii::$app->user->identity;

            // delete relation in junction table ("delete: true" means deleting row in junction table)
            $category->unlink('users', $user, true);

            if ($category->is_default == 0) { // if category was created by user, delete it from table Category
                $category->delete();
            }
        } catch (\Throwable $e) {
            Yii::$app->session->setFlash('error', 'Ошибка! Данной категории не существует.');
        } finally {
            $this->goHome();
        }
    }
}