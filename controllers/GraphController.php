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

    /**
     * This action returns graph/index.php, which includes category and charge tables. So it also prepares data for them
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

//        $data = $this->prepareDataForGraphs($charges, $categories);
        return $this->render('index',
            ['charges' => $charges,
            'categories' => $categories,
            'user_category' => $user_category]);
//            'data' => $data]);
    }

//    public function prepareDataForGraphs($charges, $categories): array {
//
////        $data['date_sum']['date'] = $this->getDates($charges);
//        $data['date_sum'] = $this->getSumByDate($charges);
//        return $data;
//    }
//
//    public function getSumByDate($charges): array {
//        // sorting charges by date
//        $this->sortByField($charges, 'date');
//
//        // get sum for each date
//        $sumByDate = [];
//        foreach ($charges as $charge) {
//            $sumByDate[$charge['date']] += $charge['amount'];
//        }
//        return $sumByDate;
//    }
//
//    public function sortByField($array, string $field) {
//        usort($array, function($a, $b) use ($field) {
//            return strcmp($a["{$field}"], $b["{$field}"]);
//        });
//    }
//
////    public function getDates($charges): array {
////        $result = [];
////        foreach ($charges as $charge) {
////            $result[] = $charge['date'];
////        }
////        return $result;
////    }
}