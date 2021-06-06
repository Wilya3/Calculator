<?php


namespace app\models;


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
class Charge extends ActiveRecord {

    public static function tableName() {
        return 'charge';
    }
}