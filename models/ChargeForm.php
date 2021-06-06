<?php


namespace app\models;


use Yii;
use yii\base\Model;

class ChargeForm extends Model {
    public $name;
    public $description;
    public $amount;
    public $date;
    public $category_id;

    public function rules() {
        return [
            [['name', 'amount'], 'required'],
            [['name', 'description'], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            ['amount', 'number'],
            ['date', 'date', 'format' => 'php:Y-m-d'],
            ['category_id', 'isCategoryBelongsThisUser']
//            ['category', 'in']
        ];
    }

    public function isCategoryBelongsThisUser(): bool {
        return !is_null(Category::findCurrentUserCategory($this->category_id));
    }

    public function save() {
        $charge = new Charge();
        $charge->name = $this->name;
        $charge->description = $this->description;
        $charge->amount = $this->amount;
        $charge->date = $this->date;
        
        $user_id = Yii::$app->user->getId();
        $charge->user_category_id = UserCategory::findOne(['user_id'=>$user_id,
                                                           'category_id'=>$this->category_id])->id;
        $charge->save();
    }
}