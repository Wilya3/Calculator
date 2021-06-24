<?php


namespace app\models;


use DateTime;
use Yii;
use yii\base\Model;

class ChargeForm extends Model {
    public $id; //need for update
    public $name;
    public $description;
    public $amount;
    public $date;
    public $category_id;
    private static $dateFormat = 'php:Y-m-d';

    public function rules(): array {
        return [
            [['name', 'amount', 'date', 'category_id'], 'required'],
            [['name', 'description'], 'filter', 'filter' => '\yii\helpers\HtmlPurifier::process'],
            ['amount', 'number'],
            ['date', 'date', 'format' => self::$dateFormat],
            ['date', 'notFuture'],
            ['category_id', 'isCategoryBelongsThisUser']
        ];
    }


    /**
     * Checks, is category linked with this charge belongs to authenticated user
     * @return bool
     */
    public function isCategoryBelongsThisUser(): bool {
        $category = Category::findOne(['id' => $this->category_id]);
        if (is_null($category)) {
            return false;
        }
        return $category->belongsThisUser();
    }

    /**
     * Checks, is date of charge not in future
     * @return bool
     */
    public function notFuture(): bool {
        $date = DateTime::createFromFormat(self::$dateFormat, $this->date);
        $today = new DateTime();
        return $date <= $today;
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