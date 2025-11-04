<?php

namespace core\forms\manage\User;

use core\entities\User\User;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yiidreamteam\upload\ImageUploadBehavior;


/**
 * Class UserCreateForm
 * @package core\forms\manage\User
 *
 * @mixin ImageUploadBehavior
 */
class UserCreateForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $first_name;
    public $last_name;
    public $role;
    public $is_banned;

    public function rules(): array
    {
        return [
            [['username', 'email', 'role'], 'required'],
            ['email', 'email'],
            [['username', 'email'], 'string', 'max' => 255],
            [['username', 'email'], 'unique', 'targetClass' => User::class],
            [['first_name', 'last_name'],  'string', 'max' => 255],
            [['is_banned'], 'integer'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'username' => Yii::t('app', 'Username'),
            'email' => Yii::t('app', 'Email'),
            'password' => Yii::t('app', 'Password'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'role' => Yii::t('app', 'Role'),
            'is_banned' => Yii::t('app', 'Is Banned'),
        ];
    }

    public function rolesList(): array
    {
        return ArrayHelper::map(\Yii::$app->authManager->getRoles(), 'name', 'description');
    }

}