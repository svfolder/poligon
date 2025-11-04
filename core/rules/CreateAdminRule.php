<?php


namespace core\rules;


use core\entities\User\User;
use core\traits\RuleTrait;
use Yii;
use yii\rbac\Rule;

/**
 *
 * @property-read bool $can
 */
class CreateAdminRule extends Rule
{

    use RuleTrait;

    public $name = 'createAdmin';

    public function execute($user, $item, $params): bool
    {
        $this->setUp();

        if (array_key_exists('super', $this->currentRoles) || array_key_exists('developer', $this->currentRoles)) {
            return true;
        }elseif (array_key_exists('admin', $this->currentRoles)){

            if (Yii::$app->request->isPost){
                return $this->getCan($this->currentAction);
            }else{
                return true;
            }

        }else{
            return false;
        }
    }

    protected function getCan($action): bool
    {
        if ($action === 'update' || $action === 'create'){

            $roleCreate = Yii::$app->request->post('UserCreateForm')['role'];
            $roleUpdate = Yii::$app->request->post('UserEditForm')['role'];

            $role = $roleCreate ? $roleCreate : $roleUpdate;

            if ( 'admin' === $role || 'super' === $role || 'developer' === $role){
                return false;
            }else{
                return true;
            }

        }elseif ($action === 'delete'){

            $id = Yii::$app->request->get('id');
            $roles = Yii::$app->authManager->getRolesByUser($id);

            if (
                array_key_exists('admin', $roles) ||
                array_key_exists('super', $roles) ||
                array_key_exists('developer', $roles)
            ){
                return false;
            }else{
                return true;
            }
        }

        return false;
    }
}