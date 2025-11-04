<?php


namespace core\traits;

use core\entities\User\User;
use Yii;

trait RuleTrait
{
    /**
     * @var User|null
     */
    protected $currentUser;

    /**
     * @var array
     */
    protected $currentRoles = [];

    /**
     * @var string
     */
    protected $currentAction = '';

    /**
     * @var int|null
     */
    protected $id = null;

    public function setUp()
    {
        $this->currentUser = $this->getCurrentUser();
        $this->currentAction = Yii::$app->controller->action->id;

        if ($this->currentUser){
            $this->currentRoles = $this->getRolesByUser($this->currentUser->id);
        }

        $this->id = (int)Yii::$app->request->get('id');
    }

    public function getCurrentUser(): ?User
    {
        return Yii::$app->user->identity->getUser();
    }

    public function getRolesByUser(int $user_id)
    {
        return Yii::$app->authManager->getRolesByUser($user_id);
    }

    public function isItMe(): bool
    {
        return $this->id == $this->currentUser->id;
    }

    public function arrayKeysExist(array $keys, array $array): bool
    {
        foreach($keys as $key){
            if(array_key_exists($key, $array)) {
                return true;
            }
        }
        return false;
    }
}