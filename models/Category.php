<?php


namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Table 'category' from DB 'calculator'
 * id INT UNSIGNED PK
 * name VARCHAR(255)
 * description TEXT NULL
 * is_default BOOL (0, 1)
 * When user break relation with this category default category will not be deleted.
 * Custom category in such situation will be deleted
 */
class Category extends ActiveRecord {

    public function getUsers() {
        return $this->hasMany(User::class, ['id' => 'user_id'])
            ->viaTable('user_category', ['category_id' => 'id']);
    }

    public static function getName(int $id) {
        return self::findOne(['id' => $id])->name;
    }

    /**
     * Get categories that had been connected to all users during registration
     * @return array|ActiveRecord[]
     */
    public static function findDefaultCategories() {
        return self::find()->where(['is_default' => 1])->all();
    }

    /**
     * Get a category by id which HAS a connection with current user.
     * If there is not category with specified id or category has not the connection with current user, return null
     * @param int $category_id
     * @return null|ActiveRecord
     */
    public static function findCurrentUserCategory(int $category_id) {
        $user = Yii::$app->user->identity;
        $categories = $user->categories;
        foreach ($categories as $category) {
            if ($category->id === $category_id) {
                return $category;
            }
        }
        return null;
    }
}
