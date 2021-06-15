<?php
use yii\data\ArrayDataProvider;
use yii\grid\GridView;
use yii\widgets\Pjax;

/**
 * If charges are shown for only one category, the category name will not be displayed
 */
$providerOptions = [
    'allModels' => $charges,
    'pagination' => [
        'pageSize' => 15,
    ],
    'sort' => [
        'attributes' => (is_null($charges[0]['category_name'])
            ? ['name', 'amount', 'date']
            : ['name', 'amount', 'category_name', 'date'])
    ],
];

$dataProvider = new ArrayDataProvider($providerOptions);

$gridOptions = [
    'id' => 'charges_gridview',
    'dataProvider' => $dataProvider,
    'options' => ['style' => 'font-size:17px;'],
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'name',
            'label' => 'Название',
            'format' => 'text'
        ],
        [
            'attribute' => 'description',
            'label' => 'Описание',
            'format' => 'text',
            'value' => function($model) { return is_null($model['description']) ? "" : $model['description']; }
        ],
        [
            'attribute' => 'amount',
            'label' => 'Цена',
            'format' => 'text'
        ],
        [
            'attribute' => 'date',
            'label' => 'Дата',
            'format' => 'date'
        ],
        [
            'class' => 'yii\grid\ActionColumn',
            'visibleButtons' => [
                'update' => true,
                'delete' => true,
                'view' => false
            ],
            'urlCreator' => function($action, $model, $key, $index) {
                if ($action === 'update') {
                    return "charge-update?id={$model['id']}";
                }
                if ($action === 'delete') {
                    return "charge-delete?id={$model['id']}";
                }
            }
        ],
    ],
];

if (!is_null($charges[0]['category_name'])) {
    $column_category = [
        'attribute' => 'category_name',
        'label' => 'Категория',
        'format' => 'text'
    ];
    $length = count($gridOptions['columns']);
    array_splice($gridOptions['columns'], $length - 1, 0, [$column_category]);
}

Pjax::begin(['id' => 'charges_pjax', 'enablePushState' => false]);
try {
    echo GridView::widget($gridOptions);
} catch (Exception $e) {
    echo "Ошибка обработки данных";
}
Pjax::end();

$url = is_null($category['id'])?"":"?category_id=".$category['id'];
?>
<div>
    <a href="/charge/charge-add<?=$url?>"
       class="btn btn-success">Добавить запись</a>
</div>
