<?php


namespace app\models;


use Yii;
use yii\db\ActiveRecord;


/**
 * Table 'charge' from DB 'calculator'
 * id INT UNSIGNED PK
 * name VARCHAR(255)
 * description TEXT
 * amount DECIMAL(13, 3)
 * date DATE
 * user_category_id INT UNSIGNED
 */
class Charge extends ActiveRecord { //TODO: Делать ли property

    public static function tableName(): string {
        return 'charge';
    }

    public function getCategory() {
        return $this->hasOne(Category::class, ['id' => 'category_id'])
            ->viaTable('user_category', ['id' => 'user_category_id']);
    }

    public function belongsThisUser(): bool {
        $user_category = UserCategory::findOne(['id' => $this->user_category_id]);
        return ($user_category->user_id == Yii::$app->user->getId());
    }
}