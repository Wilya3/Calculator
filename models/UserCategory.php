<?php


namespace app\models;


use yii\db\ActiveRecord;

class UserCategory extends ActiveRecord {

    public static function tableName() {
        return 'user_category';
    }

    public static function findUserCategory(int $user_id, int $category_id) {
        return self::findOne();
    }
}