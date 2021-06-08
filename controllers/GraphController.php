<?php


namespace app\controllers;


use app\models\Charge;
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
        $user = Yii::$app->user->identity;
        $charges = $user->chargesAsArray;  // prepare data for charge table (charge/index.php)
        $categories = $user->categoriesAsArray;  // prepare data for category table (category/index.php)
        $data = $this->prepareDataForGraphs($charges, $categories);
        return $this->render('index', ['charges' => $charges, 'categories' => $categories, 'data' => $data]);
    }

    public function prepareDataForGraphs($charges, $categories): array {

//        $data['date_sum']['date'] = $this->getDates($charges);
        $data['date_sum'] = $this->getSumByDate($charges);
        return $data;
    }

    public function getSumByDate($charges): array {
        // sorting charges by date
        $this->sortByField($charges, 'date');

        // get dates
        $date_sum['date'] = $this->getDates($charges);

        // get sum for each date
        $sumByDate = [];
        foreach ($charges as $charge) {
            $sumByDate[$charge['date']] += $charge['amount'];
        }
        $date_sum['sum'] = array_values($sumByDate);
        return $date_sum;
    }

    public function sortByField($array, string $field) {
        usort($array, function($a, $b) use ($field) {
            return strcmp($a["{$field}"], $b["{$field}"]);
        });
    }

    public function getDates($charges): array {
        $result = [];
        foreach ($charges as $charge) {
            $result[] = $charge['date'];
        }
        return $result;
    }
}