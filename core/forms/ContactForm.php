<?php

namespace core\forms;

use Yii;
use yii\base\Model;

class ContactForm extends Model
{
    public $name;
    public $email;
    public $subject;
    public $body;
    public $verifyCode;

    public function rules()
    {
        return [
            [['name', 'email', 'subject', 'body'], 'required'],
            ['email', 'email'],
            ['verifyCode', 'captcha'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'verifyCode' => Yii::t('app','Verification Code'),
            'email' => Yii::t('app','Email'),
            'subject' => Yii::t('app','Subject'),
            'body' => Yii::t('app','Body'),
            'name' => Yii::t('app','Your name')
        ];
    }
}
