<?php


namespace app\controllers;

use app\models\Category;
use Yii;
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
        $user = Yii::$app->user->identity;
        $table = $user->categories;
        return $this->render('index', ['table' => $table]);
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