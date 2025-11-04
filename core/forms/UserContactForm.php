<?php


namespace core\forms;

use core\entities\User\User;
use yii\base\Model;


class UserContactForm extends Model
{

    public $email;
    public $username;

    public $_user;

    public function __construct(User $user, $config = [])
    {
        $this->email = $user->email;
        $this->username = $user->username;
        $this->_user = $user;
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['email'], 'email'],
            [['username'], 'string'],
        ];
    }

}