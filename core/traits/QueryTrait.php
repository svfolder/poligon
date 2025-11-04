<?php


namespace core\traits;

use Yii;
use core\entities\User\User;


trait QueryTrait
{
    /** @var User */
    protected $currentUser;

    public function __construct($modelClass, $config = [])
    {
        parent::__construct($modelClass, $config);
        if (!Yii::$app->user->isGuest){
            $this->currentUser = Yii::$app->user->identity->getUser();
        }
    }

}