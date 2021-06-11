<?php


namespace app\controllers;


use app\models\Category;
use app\models\Charge;
use app\models\UserCategory;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;

class GraphController extends Controller {

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
        // TODO: виджет времени. Для конкретной категории/записи сделать
    /**
     * This action returns graph/index.php, which includes category and charge tables.
     * So it also prepares data for them
     * @return string
     */
    public function actionIndex(): string {
        $user_id = Yii::$app->user->getId();
//TODO: Удалить комменты
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
}