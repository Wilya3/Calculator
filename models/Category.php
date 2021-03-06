<?php


namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * Table 'category' from DB 'calculator'
 * - id UNSIGNED PK
 * - name VARCHAR(255)
 * - description TEXT NULL
 * - is_default BOOL (0, 1)
 * @property int id UNSIGNED PK
 * @property string name VARCHAR(255)
 * @property string description TEXT NULL
 * @property int is_default BOOL (0, 1)
 * When user break relation with this category default category will not be deleted.
 * Custom category in such situation will be deleted
 */
class Category extends ActiveRecord {


    public function getUsers(): ActiveQuery {
        return $this->hasMany(User::class, ['id' => 'user_id'])
            ->viaTable('user_category', ['category_id' => 'id']);
    }

    public function getCharges(): ActiveQuery {
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

    /**
     * Check is category belongs currently authorized user
     * @return bool
     */
    public function belongsThisUser(): bool {
        $user_id = Yii::$app->user->getId();
        $user_category = UserCategory::find()->where(['category_id' => $this->id])->andWhere(['user_id' => $user_id])->asArray()->one();
        return !is_null($user_category);
    }

    /**
     * Get categories that had been connected to all users during registration
     * @return array|ActiveRecord[]
     */
    public static function findDefaultCategories(): array {
        return self::find()->where(['is_default' => 1])->all();
    }
}
