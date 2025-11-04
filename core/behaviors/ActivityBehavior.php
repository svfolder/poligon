<?php

namespace core\behaviors;

use core\entities\User\User;
use core\repositories\user\UserRepository;
use Yii;
use yii\base\Behavior;


class ActivityBehavior extends Behavior
{
    /**
     * @var User
     */
    private $_user;

    /**
     * @var UserRepository
     */
    private $users;

    public function events(): array
    {
        return [
            \yii\web\Controller::EVENT_AFTER_ACTION => 'afterAction',
        ];
    }

    public function __construct(UserRepository $users, $config = [])
    {
        parent::__construct($config);
        $this->users = $users;

        if (!Yii::$app->user->isGuest){
            $this->_user = \Yii::$app->user->identity->getUser();
        }
    }

    public function afterAction($action)
    {
        $this->_user->activity();
        $this->users->save($this->_user);
    }

}