<?php

namespace core\forms\User;

use Yii;
use core\entities\User\User;
use core\entities\User\UserQuery;
use yii\web\UploadedFile;
use yiidreamteam\upload\ImageUploadBehavior;

/**
 * Class UserForm
 * @package core\forms
 *
 * @mixin ImageUploadBehavior
 */
class UserForm extends Yii\base\Model
{
    public $username;
    public $first_name;
    public $last_name;
    public $image;
    public $auth_key;
    public $password_hash;
    public $password_reset_token;
    public $email;
    public $status;
    public $created_at;
    public $updated_at;
    public $email_confirm_token;
    public $is_banned;
    public $activity_at;
    public $role;

    /**
     * @var User
     */
    protected $_user;

    public function __construct(User $user = null, $config = [])
    {
        if ($user) {
            $this->username = $user->username;
            $this->first_name = $user->first_name;
            $this->last_name = $user->last_name;
            $this->image = $user->image;
            $this->auth_key = $user->auth_key;
            $this->password_hash = $user->password_hash;
            $this->password_reset_token = $user->password_reset_token;
            $this->email = $user->email;
            $this->status = $user->status;
            $this->created_at = $user->created_at;
            $this->updated_at = $user->updated_at;
            $this->email_confirm_token = $user->email_confirm_token;
            $this->is_banned = $user->is_banned;
            $this->activity_at = $user->activity_at;
        } else {

        }

        $this->_user = $user;

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['status', 'created_at', 'updated_at', 'is_banned', 'activity_at'], 'integer'],
            [['username', 'first_name', 'last_name', 'password_hash', 'password_reset_token', 'email', 'email_confirm_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['email'], 'unique', 'filter' => $this->_user ? ['<>', 'id', $this->_user->id] : null, ],
            [['username'], 'unique'],
            [['password_reset_token'], 'unique'],
            ['image', 'file', 'extensions' => 'jpg, jpeg, png'],
            [['username', 'email', 'role'], 'required'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Username'),
            'first_name' => Yii::t('app', 'First Name'),
            'last_name' => Yii::t('app', 'Last Name'),
            'image' => Yii::t('app', 'Image'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'email' => Yii::t('app', 'Email'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
            'email_confirm_token' => Yii::t('app', 'Email Confirm Token'),
            'is_banned' => Yii::t('app', 'Is Banned'),
            'activity_at' => Yii::t('app', 'Activity At'),
            'role' => Yii::t('app', 'Role'),
        ];
    }

    public function beforeValidate(): bool
    {
        if (parent::beforeValidate()) {
            $this->image = UploadedFile::getInstance($this, 'image');
            return true;
        }
        return false;
    }

    public static function getDb(): Yii\db\Connection
    {
        return Yii::$app->getDb();
    }

    public static function find(): UserQuery
    {
        return new UserQuery(get_called_class());
    }

    public static function tableName(): string
    {
        return 'user';
    }

    public function dataModel(): ?User
    {
        return $this->_user;
    }
}
