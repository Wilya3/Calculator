<?php


namespace app\models;

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

    /**
     * Get categories that had been connected to all users during registration
     * @return array|ActiveRecord[]
     */
    public static function findDefaultCategories() {
        return self::find()->where(['is_default' => 1])->all();
    }

    /**
     * @param int $id
     * @return null|ActiveRecord
     */
    public static function findCategory($id) {
         return self::findOne(['id' => $id]);
    }
}
