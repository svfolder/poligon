<?php

namespace core\forms\manage\User;

use core\entities\User\User;
use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yiidreamteam\upload\ImageUploadBehavior;

/**
 * Class UserEditForm
 * @package core\forms\manage\User
 *
 * @mixin ImageUploadBehavior
 */
class UserEditForm extends Model
{
    public $username;
    public $email;
    public $first_name;
    public $last_name;
    public $image;
    public $role;
    public $is_banned;

    public $_user;

    public function __construct(User $user, $config = [])
    {
        $this->username = $user->username;
        $this->email = $user->email;
        $this->first_name = $user->first_name;
        $this->last_name = $user->last_name;
        $this->image = $user->image;
        $roles = Yii::$app->authManager->getRolesByUser($user->id);
        $this->role = $roles ? reset($roles)->name : null;
        $this->is_banned = $user->is_banned;
        $this->_user = $user;
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['username', 'email', 'role'], 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            [['first_name', 'last_name'],  'string', 'max' => 255],
            ['image', 'file', 'extensions' => 'jpg, jpeg, png'],
            [['username', 'email'], 'unique', 'targetClass' => User::class, 'filter' => ['<>', 'id', $this->_user->id]],
            [['is_banned'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'Username'),
            'email' => Yii::t('app', 'Email'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'phone' => Yii::t('app', 'Phone'),
            'password' => Yii::t('app', 'Password'),
            'role' => Yii::t('app', 'Role'),
            'is_banned' => Yii::t('app', 'Is Banned'),
        ];
    }

    public function rolesList(): array
    {
        return ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description');
    }
}