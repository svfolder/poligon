<?php


namespace core\entities\User;


use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;

trait UserSearchTrait
{
    public $id;
    public $date_from;
    public $date_to;
    public $username;
    public $email;
    public $status;
    public $first_name;
    public $last_name;
    public $role;
    public $is_banned;

    /**
     * @return array
     */
    public function scenarios(): array
    {
        return Model::scenarios();
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'username' => Yii::t('app', 'Username'),
            'email' => Yii::t('app', 'Email'),
            'id' => Yii::t('app', 'ID'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    public function rolesList(): array
    {
        return ArrayHelper::map(\Yii::$app->authManager->getRoles(), 'name', 'description');
    }

}