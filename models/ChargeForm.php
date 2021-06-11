<?php


namespace app\models;


use Yii;
use yii\base\Model;

class ChargeForm extends Model {
    public $id; //need for update
    public $name;
    public $description;
    public $amount;
    public $date;
    public $category_id;

    public function rules(): array {  // TODO: разобраться с датой
        return [
            [['name', 'amount', 'category_id'], 'required'],
            [['name', 'description'], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            ['amount', 'number'],
            ['date', 'date', 'format' => 'php:Y-m-d'],
            ['category_id', 'isCategoryBelongsThisUser']
        ];
    }

    public function isCategoryBelongsThisUser(): bool {
        $category = Category::findOne(['id' => $this->category_id]);
        if (is_null($category)) {
            return false;
        }
        return $category->belongsThisUser();
    }

    public function save() {
        $charge = Charge::findOne(['id' => $this->id]);
        if (is_null($charge)) {
            $charge = new Charge();
        }
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