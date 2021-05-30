<?php


namespace app\models;

use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;

/**
 * Table 'category' from DB 'calculator'
 * id INT UNSIGNED PK
 * name VARCHAR(255)
 * description TEXT NULL
 * is_default BOOL
 */
class Category extends ActiveRecord {

    public function getUsers() {
        return $this->hasMany(User::class, ['id' => 'user_id'])
            ->viaTable('user_category', ['category_id' => 'id']);
    }

    public static function findDefaultCategories() {
        return self::find()->where(['is_default' => 1])->all();
    }

    /**
     * Get user's and default categories from DB
     * @param string $user_id
     * @return array
     */
    public static function findCategoriesForUser($user_id) {
        return [1, 2, 3]; // TODO: Fix query
    }
}