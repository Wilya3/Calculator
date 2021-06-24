<?php


namespace app\models;


use yii\db\ActiveRecord;

/**
 * Junction table "user_category" from "calculator" DB.
 * - id INT
 * - user_id INT
 * - category_id INT
 * @property int id
 * @property int user_id
 * @property int category_id
 */
class UserCategory extends ActiveRecord {

    public function getChargesAsArray(): array {
        return $this->hasMany(Charge::class, ['user_category_id' => 'id'])->asArray()->all();
    }

    public function getCategoriesAsArray(): array {
        return $this->hasOne(Category::class, ['id' => 'category-id'])->asArray()->all();
    }

    public static function tableName(): string {
        return 'user_category';
    }
}