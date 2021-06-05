<?php


namespace app\models;


use yii\db\ActiveRecord;


/**
 * Table 'charge' from DB 'calculator'
 * id INT UNSIGNED PK
 * username VARCHAR(30) UK
 * password VARCHAR(255)
 * email VARCHAR(255) UK
 */
class Charge extends ActiveRecord {

    public static function tableName() {
        return 'charge';
    }
}