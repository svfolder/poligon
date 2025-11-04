<?php

namespace core\entities\User;

use Yii;

/**
 * This is the model class for table "user_tokens".
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $new_password
 * @property string|null $new_email
 * @property string|null $new_username
 * @property string|null $token_password
 * @property string|null $token_email
 * @property string|null $token_username
 *
 * @property User $user
 */
class UserToken extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_tokens';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['new_password', 'new_email', 'new_username', 'token_password', 'token_email', 'token_username'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'new_password' => Yii::t('app', 'New Password'),
            'new_email' => Yii::t('app', 'New Email'),
            'new_username' => Yii::t('app', 'New Username'),
            'token_password' => Yii::t('app', 'Token Password'),
            'token_email' => Yii::t('app', 'Token Email'),
            'token_username' => Yii::t('app', 'Token Username'),
        ];
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     * @return UserTokenQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserTokenQuery(get_called_class());
    }
}
