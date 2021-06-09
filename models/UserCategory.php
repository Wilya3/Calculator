<?php


namespace app\models;


use yii\db\ActiveRecord;

class UserCategory extends ActiveRecord {

    public function getChargesAsArray() {
        return $this->hasMany(Charge::class, ['user_category_id' => 'id'])->asArray()->all();
    }

    public function getCategoriesAsArray() {
        return $this->hasOne(Category::class, ['id' => 'category-id'])->asArray()->all();
    }

    public static function tableName() {
        return 'user_category';
    }
}