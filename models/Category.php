<?php


namespace app\models;

use \yii\db\ActiveRecord;

/**
 * Table 'category' from DB 'calculator'
 * id INT UNSIGNED PK
 * title VARCHAR(255) UK
 * description TEXT
 * user_id INT UNSIGNED FK
 */
class Category extends ActiveRecord {

    /**
     * Get user's and default categories from DB
     * @param string $user_id
     * @return array
     */
    public function findCategories($user_id) {
        return self::find()->where(['user_id', $user_id])->asArray()->all();
    }
}