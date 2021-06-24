<?php


namespace app\controllers;

use app\models\Category;
use app\models\CategoryForm;
use Throwable;
use Yii;
use yii\db\Exception;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
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
        ];
    }  // If any error occurred, redirect to site/index. If logged, redirect to graph/index

    /**
     * Renders all categories that belong this user.
     * Adds 'sum' column to the each category. It represents sum of charges for this category.
     * Categories are returned as array.
     * @return string render with layout or without if ajax
     */
    public function actionIndex(): string {
        $user_id = Yii::$app->user->getId();
        $categories = Category::find()
            ->select(['`category`.*', 'SUM(`charge`.`amount`) AS sum'])
            ->join('INNER JOIN', 'user_category', '`category`.`id` = `user_category`.`category_id`')
            ->join('LEFT JOIN', 'charge', '`user_category`.`id` = `charge`.`user_category_id`')
            ->where("`user_category`.`user_id` = $user_id")
            ->groupBy(['`category`.`id`'])
            ->asArray()
            ->all();

        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('category_grid_view', ['categories' => $categories]);
        }
        return $this->render('category_grid_view', ['categories' => $categories]);
    }

    /**
     * Renders empty category form.
     * If POST - adds a new category, validate, save.
     * @return string|Response
     */
    public function actionCategoryAdd() {
        $model = new CategoryForm();
        if ($model->load(Yii::$app->request->post(), 'CategoryForm')) {
            if ($model->validate()) {
                $model->save();
                return $this->goHome();
            }
        }
        return $this->render('category_add', ['model' => $model]);
    }

    /**
     * Renders category with $id. Changes category if POST.
     * Only a custom category owned by current user can be changed.
     * @param int $id category id to update
     * @return string|Response redirect home
     * @throws BadRequestHttpException If category is not found or has not any relation with user
     * @throws ForbiddenHttpException If category is default
     * or if it is default category, which cannot be changed
     */
    public function actionCategoryUpdate(int $id) {
        // Check category changing is allowed
        $category = Category::findOne(['id' => $id]);
        if (is_null($category) || !$category->belongsThisUser()) {
            throw new BadRequestHttpException();
        } elseif ($category->is_default == 1) {
            throw new ForbiddenHttpException();
        }

        $model = new CategoryForm();
        // Validate POST-data and save
        if ($model->load(Yii::$app->request->post(), 'CategoryForm')) {
            $model->id = $id;
            if ($model->validate()) {
                $model->save();
                return $this->goHome();
            }
        }
        // If GET-request - set values into the model
        $model->setAttributes($category->attributes);
        return $this->render('category_update', ['model' => $model]);
    }

    /**
     * Deletes the relation between this (authorized) user and category with $id.
     * If the category belongs to the user, it will be also deleted from 'category' table.
     * @param int $id category id to delete
     * @return Response redirect home
     * @throws BadRequestHttpException If category is not found or has not any relation with user
     * @throws StaleObjectException
     * @throws Exception
     * @throws Throwable
     */
    public function actionCategoryDelete(int $id): Response {
        // Check category deleting is allowed
        $category = Category::findOne(['id' => $id]);
        if (is_null($category) && !$category->belongsThisUser()) {
            throw new BadRequestHttpException();
        }
        // delete relation from junction table
        $category->unlink('users', Yii::$app->user->identity, true);

        if ($category->is_default == 0) { // if category was created by user, delete it from table Category
            $category->delete();
        }
        return $this->goHome();
    }
}