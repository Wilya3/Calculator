<?php


namespace app\models;

use Yii;
use yii\base\Model;

class CategoryForm extends Model {
    public $id;  // need for update action
    public $name;
    public $description;
    public $is_default;  // need for update action


    public function rules(): array {
        return [
            ['name', 'required'],
            ['name', 'uniqueName', 'when' => function() {
                    return $this->isAddingCategory() || $this->isNameChanged();
                }],
            [['name'], 'string', 'max' => 255],
            [['name','description'],'filter','filter'=>'\yii\helpers\HtmlPurifier::process'], //css, xss
            ['id', 'safe']  // https://www.yiiframework.com/doc/guide/2.0/en/structure-models#safe-attributes
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
    }

    /**
     * Check new name is not equals name in DB
     * @return bool
     */
    public function isNameChanged(): bool {
        return (!is_null($this->id) && Category::getName($this->id) !== $this->name);
    }

    /**
     * Check, is category being added
     * @return bool
     */
    public function isAddingCategory(): bool {
        return is_null($this->id);  // If category is being added, it has not an id
    }
}
