<?php

namespace core\entities\User;

use Yii;
use core\entities\Auth\AuthItem;
use core\entities\Auth\AuthItemQuery;
use core\traits\ModelTrait;
use core\traits\UserTrait;
use yii\base\Exception;
use yii\db\ActiveRecord;
use yiidreamteam\upload\ImageUploadBehavior;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string|null $username
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $image
 * @property string $auth_key
 * @property string|null $password_hash
 * @property string|null $password_reset_token
 * @property string|null $email
 * @property int $status
 * @property int $created_at
 * @property int $updated_at
 * @property string|null $email_confirm_token
 * @property int $is_banned
 * @property int $activity_at
 *
 * @property AuthAssignment[] $authAssignments
 * @property AuthItem[] $itemNames
 * @property UserNetwork[] $userNetworks
 * @property UserToken[] $userTokens
 *
 * @mixin ImageUploadBehavior
 */
class User extends ActiveRecord
{
    use ModelTrait;
    use UserTrait;

    public const STATUS_BANNED = -10;
    public const STATUS_WAIT = 0;
    public const STATUS_ACTIVE = 10;
    public const BANNED_YES = 1;
    public const BANNED_NO = 0;

    public function behaviors(): array
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
            'image-upload-behavior' => [
                'class' => '\yiidreamteam\upload\ImageUploadBehavior',
                'attribute' => 'image',
                'thumbs' => [
                    'default' => ['width' => 240, 'height' => 120],
                ],
                'filePath' => '@upload/user/[[pk]].[[extension]]',
                'fileUrl' => '/upload/user/[[pk]].[[extension]]',
                'thumbPath' => '@upload/user/[[profile]]_[[pk]].[[extension]]',
                'thumbUrl' => '/upload/user/[[profile]]_[[pk]].[[extension]]',
                'createThumbsOnRequest' => true,
            ],
        ];
    }

    public static function tableName(): string
    {
        return 'user';
    }

    /**
     * @throws Exception
     */
    public static function create(
        $username,
        $first_name,
        $last_name,
        $image,
        $email,
        $is_banned,
        $password = null
    ): self {
        $username = $username ? $username : null;
        $first_name = $first_name ? $first_name : null;
        $last_name = $last_name ? $last_name : null;
        $image = $image ? $image : null;
        $email = $email ? $email : null;

        $user = new static();
        $user->username = $username;
        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->image = $image;
        $user->email = $email;
        $user->is_banned = $is_banned;

        $user->setPassword(!empty($password) ? $password : Yii::$app->security->generateRandomString());
        $user->status = self::STATUS_ACTIVE;
        $user->created_at = time();
        $user->generateAuthKey();
        return $user;
    }

    public function edit($username, $first_name, $last_name, $image, $email, $is_banned): void
    {
        $username = $username ? $username : null;
        $first_name = $first_name ? $first_name : null;
        $last_name = $last_name ? $last_name : null;
        $image = $image ? $image : null;
        $email = $email ? $email : null;

        $this->username = $username;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->image = $image;
        $this->email = $email;
        $this->is_banned = $is_banned;
    }

    public function rules(): array
    {
        return [
            [['status', 'created_at', 'updated_at', 'is_banned', 'activity_at'], 'integer'],
            [['username', 'first_name', 'last_name', 'password_hash', 'password_reset_token', 'email', 'email_confirm_token'], 'string', 'max' => 255],
            [['auth_key'], 'string', 'max' => 32],
            [['email'], 'unique'],
            [['username'], 'unique'],
            [['password_reset_token'], 'unique'],
            ['image', 'file', 'extensions' => 'jpg, jpeg, png'],
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

    public function getAuthAssignments()
    {
        return $this->hasMany(AuthAssignment::className(), ['user_id' => 'id']);
    }

    public function getItemNames()
    {
        return $this->hasMany(AuthItem::className(), ['name' => 'item_name'])->viaTable('auth_assignment', ['user_id' => 'id']);
    }

    public function getUserNetworks()
    {
        return $this->hasMany(UserNetwork::className(), ['user_id' => 'id']);
    }

    public function getUserTokens()
    {
        return $this->hasMany(UserToken::className(), ['user_id' => 'id']);
    }

    public static function find(): UserQuery
    {
        return new UserQuery(get_called_class());
    }
}
