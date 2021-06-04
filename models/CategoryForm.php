<?php


namespace app\models;

use Yii;
use yii\base\Model;

class CategoryForm extends Model {
    public $id;  // need for update action
    public $name;
    public $description;
    public $is_default;  // need for update action


//    const SCENARIO_ADD = 'add';
//
//    public function scenarios() {
//        $scenarios = parent::scenarios();
//        $scenarios[self::SCENARIO_ADD] = ['name', 'description'];
//        return $scenarios;
//    }

    public function rules(): array {
        return [
            ['name', 'required'],
            ['name', 'uniqueName', 'when' => function() {
                    return $this->isAddingCategory() || $this->isNameChanged();
                }],
            [['name'], 'string', 'max' => 255],
            [['name','description'],'filter','filter'=>'\yii\helpers\HtmlPurifier::process'], //css, xss
            ['id', 'safe'],  // https://www.yiiframework.com/doc/guide/2.0/en/structure-models#safe-attributes
            ['is_default', 'safe']
        ];
    }

    /**
     * Validate if there is equal name in categories linked with CURRENT user
     */
    public function uniqueName($attribute) {
        $categories = Yii::$app->user->identity->categories; // (ActiveRecord) User->getCategories()->hasMany()
        foreach($categories as $category) {
            if ($category->name === $this->name) {
                $this->addError($attribute, 'Категория с таким именем уже существует!');
            }
        }
    }

    /**
     * Save category and create link in junction table with current user.
     * Category will be saved as custom (not default).
     * See also models/Category.php for more information
     */
    public function save() {
        $category = Category::findOne(['id' => $this->id]);
        if (is_null($category)) {
            $category = new Category();
        }
        $category->name = $this->name;
        $category->description = $this->description;
        $category->is_default = 0;
        $category->save();
        $category->link('users', Yii::$app->user->identity);
    }

    public function isNameChanged(): bool { // TODO: Такую бизнес-логику надо выносить куда-то
        return (!is_null($this->id) && Category::getName($this->id) !== $this->name);
    }

    // TODO: просто проверять на id === null (id в форме используется только при update), либо проверять на сценарий добавления?
    public function isAddingCategory(): bool {
        return is_null($this->id);
    }
}
