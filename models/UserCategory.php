<?php


namespace app\models;


use yii\db\ActiveRecord;

class UserCategory extends ActiveRecord {

    public function getCharges() {
        $this->hasMany(Charge::class, ['user_category_id' => 'id']);
    }

    public static function tableName() {
        return 'user_category';
    }
}