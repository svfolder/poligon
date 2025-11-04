<?php

namespace console\controllers;

use core\entities\User\User;
use core\rules\BannedRule;
use core\rules\CreateAdminRule;
use core\rules\DemoRule;
use core\services\user\UserService;
use Yii;
use yii\console\Controller;
use yii\console\Exception;
use yii\helpers\ArrayHelper;

/**
 * Interactive console roles manager
 */
class RoleController extends Controller
{
    private $service;

    public function __construct($id, $module, UserService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    /**
     * Adds role to user
     * @throws Exception
     */
    public function actionAssign(): void
    {
        $username = $this->prompt('Username:', ['required' => true]);
        $user = $this->findModel($username);
        $role = $this->select('Role:', ArrayHelper::map(Yii::$app->authManager->getRoles(), 'name', 'description'));
        $this->service->assignRole($user->id, $role);
        $this->stdout('Done!' . PHP_EOL);
    }

    /**
     * @throws \Exception
     */
    public function actionRule()
    {
        $auth = Yii::$app->authManager;

        //  Создаем правило
        $bannedRule = new BannedRule();
        $auth->add($bannedRule);

        //  Создаем разрешение, и привязываем к нему правило
        $doBanned = $auth->createPermission('doBanned');
        $doBanned->description = 'Бан только узеров';
        $doBanned->ruleName = $bannedRule->name;
        $auth->add($doBanned);

        //  Достаем из базы роль менеджера
        $manager = $auth->getRole('manager');

        //  Делаем наследование
        $auth->addChild($manager, $doBanned);

        ##########################

        // Создаем правило
        $createAdminRule = new CreateAdminRule();
        $auth->add($createAdminRule);

        $createAdmin = $auth->createPermission('createAdmin');
        $createAdmin->description = 'Разрешение на создание и редактирование админов';
        $createAdmin->ruleName = $createAdminRule->name;
        $auth->add($createAdmin);

        //  Достаем роль супер и админа
        $super = $auth->getRole('super');
        $admin = $auth->getRole('admin');

        //  Делаем наследование
        $auth->addChild($super, $createAdmin);
        $auth->addChild($admin, $createAdmin);

        ##########################

        $demoRule = new DemoRule();
        $auth->add($demoRule);

        $stopDemo = $auth->createPermission('stopDemo');
        $stopDemo->description = 'Запрет для Demo';
        $stopDemo->ruleName = $demoRule->name;
        $auth->add($stopDemo);

        $demo = $auth->getRole('demo');
        $auth->addChild($manager, $stopDemo);

        $svfolder = $this->findModel('svfolder');
        $this->service->assignRole($svfolder->id, 'developer');

    }

    /**
     * @throws Exception
     */
    private function findModel($username): User
    {
        if (!$model = User::findOne(['username' => $username])) {
            throw new Exception('User is not found');
        }
        return $model;
    }
}