<?php


namespace app\models;

use Yii;
use \yii\base\Model;

class CategoryForm extends Model {

    public $title;
    public $description;

    public function rules() {
        return [
            ['title', 'required'],
            [['title'], 'string', 'max' => 255]
        ];
    }

    public function save() {
        $category = new Category();
        $category->title = $this->title;
        $category->description = $this->title;
        $category->user_id = Yii::$app->user->id;
        $category->save();
    }
}