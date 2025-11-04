<?php


namespace core\rules;

use core\traits\RuleTrait;
use Yii;
use yii\rbac\Item;
use yii\rbac\Rule;


class DemoRule extends Rule
{
    use RuleTrait;

    public $name = 'stopDemo';

    /**
     * @param integer $user
     * @param Item $item
     * @param array $params
     * @return bool
     */
    public function execute($user, $item, $params): bool
    {
        $this->setUp();

        if ($this->arrayKeysExist(['manager', 'admin', 'super', 'developer'], $this->currentRoles)) {
            return true;
        }elseif (array_key_exists('demo', $this->currentRoles)){

            if (Yii::$app->request->isPost){
                return false;
            }else{
                if ($this->currentAction == 'active'){
                    return false;
                }else{
                    return true;
                }
            }

        }else{
            return false;
        }
    }

}