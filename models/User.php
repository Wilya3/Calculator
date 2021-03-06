<?php


namespace app\models;

use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * Table 'user' from DB 'calculator'
 * - id UNSIGNED PK
 * - username VARCHAR(30) UK
 * - password VARCHAR(255)
 * - email VARCHAR(255) UK
 * - auth_key VARCHAR(255)
 * - secret_key VARCHAR(255) email validation
 * @property int id UNSIGNED PK
 * @property string username VARCHAR(30) UK
 * @property string password VARCHAR(255)
 * @property string email VARCHAR(255) UK
 * @property string auth_key VARCHAR(255)
 * @property string secret_key VARCHAR(255)
 */
class User extends ActiveRecord implements IdentityInterface {

    public function getCategories(): ActiveQuery {
	    return $this->hasMany(Category::class, ['id' => 'category_id'])
            ->viaTable('user_category', ['user_id' => 'id']);
    }

    public function getCategoriesAsArray(): array {
        return $this->hasMany(Category::class, ['id' => 'category_id'])
            ->viaTable('user_category', ['user_id' => 'id'])->asArray()->all();
    }

    public function getCharges(): ActiveQuery {
        return $this->hasMany(Charge::class, ['user_category_id' => 'id'])
            ->viaTable('user_category', ['user_id' => 'id'])->with('category');
    }

    public function getChargesAsArray(): array {
        return $this->hasMany(Charge::class, ['user_category_id' => 'id'])
            ->viaTable('user_category', ['user_id' => 'id'])->asArray()->all();
    }

    /**
     * Add relation with default categories via junction table
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes) {
        parent::afterSave($insert, $changedAttributes);
        // If user is new, then add relations with default categories
        if ($insert) {
            $defaultCategories = Category::findDefaultCategories();
            foreach ($defaultCategories as $category) {
                $this->link('categories', $category);
            }
        }
    }

    /**
     * Get the user from DB by id as ActiveRecord object.
     * @param string|int $id the ID to be looked for
     * @return ActiveRecord|null the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return self::findOne(['id'=>$id]);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface|null the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * The returned key is used to validate session and auto-login (if [[User::enableAutoLogin]] is enabled).
     *
     * Make sure to invalidate earlier issued authKeys when you implement force user logout, password change and
     * other scenarios, that require forceful access revocation for old sessions.
     *
     * @return string|null a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * @param string $authKey the given auth key
     * @return bool|null whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $authKey === $this->auth_key;
    }
}