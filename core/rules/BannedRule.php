<?php


namespace core\rules;

use core\traits\RuleTrait;
use Yii;
use yii\rbac\Item;
use yii\rbac\Rule;

class BannedRule extends Rule
{
    use RuleTrait;

    public $name = 'doBanned';

    /**
     * @param integer $user
     * @param Item $item
     * @param array $params
     * @return bool
     */
    public function execute($user, $item, $params): bool
    {
        $this->setUp();

        if ($this->isItMe()){
            return false;
        }

        if ($this->arrayKeysExist(['super', 'developer'], $this->currentRoles)) {
            return true;
        }elseif (array_key_exists('admin', $this->currentRoles)){

            if (Yii::$app->request->isPost){
                return $this->getCan(['admin', 'super', 'developer']);
            }else{
                return true;
            }

        }elseif (array_key_exists('manager', $this->currentRoles)){

            if (Yii::$app->request->isPost){
                return $this->getCan(['manager', 'demo', 'admin', 'super', 'developer']);
            }else{
                return true;
            }

        }else{
            return false;
        }

    }

    protected function getCan(array $checkRoles): bool
    {
        $roles = array_flip(array_keys($this->getRolesByUser($this->id)));

        if ($this->arrayKeysExist($checkRoles, $roles)){
            return false;
        }else{
            return true;
        }
    }
}