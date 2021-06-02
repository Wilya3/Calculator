<?php


namespace app\models;

use Yii;
use yii\base\Model;

class CategoryForm extends Model {
    public $id;  // need for update action
    public $name;
    public $description;
    public $is_default;  // need for update action
    const SCENARIO_ADD = 'add';

    public function scenarios() {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_ADD] = ['name', 'description'];
        return $scenarios;
    }

    public function rules(): array {
        return [
            ['name', 'required'],
            ['name', 'uniqueName', 'when' => function() {
            // validate unique name only if current name has been changed or if adding a new category
                    if ($this->scenario === CategoryForm::SCENARIO_ADD) return true;
                    return (!is_null($this->id) && Category::getOldName($this->id) !== $this->name);
                }],
            [['name'], 'string', 'max' => 255],
            [['name','description'],'filter','filter'=>'\yii\helpers\HtmlPurifier::process'], //css, xss
            ['id', 'safe'], // need for update action
            ['is_default', 'safe'] // https://www.yiiframework.com/doc/guide/2.0/en/structure-models#safe-attributes
        ];
    }

    /**
     * Validate if there is equal name in categories linked with this user
     */
    public function uniqueName($attribute) {
        $categories = Yii::$app->user->identity->categories;
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
}
