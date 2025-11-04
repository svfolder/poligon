<?php


namespace core\forms;

use yii\base\Model;


class PasswordForm extends Model
{

    public $password;
    public $new_password;

    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    public function rules()
    {
        return [
            [['password'], 'string'],
            [['new_password'], 'string'],
        ];
    }

}