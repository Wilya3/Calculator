<?php


namespace app\models;


use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;


/**
 * Table 'charge' from DB 'calculator'
 * - id UNSIGNED PK
 * - name VARCHAR(255)
 * - description TEXT
 * - amount DECIMAL(13, 3)
 * - date DATE
 * - user_category_id INT
 * @property int id UNSIGNED PK
 * @property string name VARCHAR(255)
 * @property string description TEXT
 * @property float amount DECIMAL(13, 3)
 * @property string date DATE
 * @property int user_category_id UNSIGNED
 */
class Charge extends ActiveRecord {

    public static function tableName(): string {
        return 'charge';
    }

    public function getCategory(): ActiveQuery {
        return $this->hasOne(Category::class, ['id' => 'category_id'])
            ->viaTable('user_category', ['id' => 'user_category_id']);
    }

    /**
     * Check is category belongs currently authorized user
     * @return bool is category belongs user
     */
    public function belongsThisUser(): bool {
        $user_category = UserCategory::find()->where(['id' => $this->user_category_id])->asArray()->one();
        return $user_category['user_id'] == Yii::$app->user->getId();
    }

    /**
     * Find sum of charges for category
     * @param int $user_category_id
     * @return float
     */
    public static function sumOfChargesByCategory(int $user_category_id) {
        $charges = Charge::find()->where(['user_category_id' => $user_category_id])->asArray()->all();
        if (is_null($charges)) {
            return 0;
        }
        $sum = 0;
        foreach ($charges as $charge) {
            $sum += $charge['amount'];
        }
        return $sum;
    }
}