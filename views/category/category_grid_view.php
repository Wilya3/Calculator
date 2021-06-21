
<?php
/**
 * @var $categories app\models\Category
 */

use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;

if (Yii::$app->session->hasFlash('error')) {
    $flash = Yii::$app->session->getFlash('error');
}

$dataProvider = new ArrayDataProvider([
    'allModels' => $categories,
    'pagination' => [
        'pageSize' => 5,
    ],
    'sort' => [
        'attributes' => ['name', 'sum'],
    ],
]);
Pjax::begin(['id' => 'categories_pjax', 'enablePushState' => false]);
try {
    echo GridView::widget([
        'id' => 'categories_gridview',
        'dataProvider' => $dataProvider,
        'options' => ['style' => 'font-size:17px;'],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'name',
                'label' => 'Название',
                'value' => function($model) {
                    $url = Url::to(["/charge/charges-by-category?id={$model['id']}"]);
                    return Html::a(Html::encode($model['name']), $url);
                },
                'format' => 'raw'
            ],
            [
                'attribute' => 'description',
                'label' => 'Описание',
                'format' => 'text',
                'value' => function($model) { return is_null($model['description']) ? "" : $model['description']; }
            ],
            [
                'attribute' => 'sum',
                'label' => 'Сумма',
                'format' => 'text',
                'value' => function($model) { return is_null($model['sum']) ? 0 : $model['sum']; }
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'visibleButtons' => [
                    'update' => function($model) {
                        return $model['is_default'] == 0;
                    },
                    'delete' => true,
                    'view' => false
                ],
                'urlCreator' => function($action, $model, $key, $index) {
                    if ($action === 'update') {
                        return "/category/category-update?id={$model['id']}";
                    }
                    if ($action === 'delete') {
                        return "/category/category-delete?id={$model['id']}";
                    }
                    return "";
                }
            ],
        ],
    ]);
} catch (Exception $e) {
    echo "Ошибка обработки данных";
}
Pjax::end();
?>
<div>
    <a href="/category/category-add" class="btn btn-success">Добавить категорию</a>
</div>
