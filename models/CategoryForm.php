<?php


namespace app\models;

use Yii;
use yii\base\Model;
use yii\base\UserException;

class CategoryForm extends Model {

    public $name;
    public $description;

    public function rules() {
        return [
            ['name', 'required'],
            ['name', 'uniqueName'],
            [['name'], 'string', 'max' => 255],
            [['name','description'],'filter','filter'=>'\yii\helpers\HtmlPurifier::process'] //css, xss
        ];
    }

    /**
     * Validate if there is equal name in categories linked with this user
     * @throws UserException
     */
    public function uniqueName() {
        $categories = Yii::$app->user->identity->categories;
        foreach($categories as $category) {
            if ($category->name === $this->name) {
                throw new UserException();
            }
        }
    }

    /**
     * Save category and create link in junction table with current user.
     * Category will be saved as custom (not default).
     * See also models/Category.php for more information
     */
    public function save() {
        $category = new Category();
        $category->name = $this->name;
        $category->description = $this->description;
        $category->is_default = 0;
        $category->save();
        $category->link('users', Yii::$app->user->identity);
    }
}
