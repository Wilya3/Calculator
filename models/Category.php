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

    public function getCharges() {
        return $this->hasMany(Charge::class, ['user_category_id' => 'id'])
            ->viaTable('user_category', ['category_id' => 'id']);
    }

    public static function getName(int $id) {
        return self::findOne(['id' => $id])->name;
    }

    /**
     * Add relation between saved category and current user in junction table if category is new.
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            $this->link('users', Yii::$app->user->identity);
        }
    }

    public function belongsThisUser(): bool {
        $user_category = UserCategory::findOne(['category_id' => $this->id]);
        return ($user_category->user_id == Yii::$app->user->getId());
    }

    /**
     * Get categories that had been connected to all users during registration
     * @return array|ActiveRecord[]
     */
    public static function findDefaultCategories() {
        return self::find()->where(['is_default' => 1])->all();
    }
}
