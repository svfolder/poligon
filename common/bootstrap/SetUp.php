<?php

namespace common\bootstrap;

use core\services\ContactService;
use dmstr\widgets\Menu;
use Yii;
use yii\base\BootstrapInterface;
use yii\bootstrap\Nav;
use yii\mail\MailerInterface;
use yii\rbac\ManagerInterface;

class SetUp implements BootstrapInterface
{
    public function bootstrap($app): void
    {
        $container = \Yii::$container;

        $skin = env('SKIN') ?: 'default';
        Yii::setAlias('@theme', "@root/themes/{$skin}/views");

        $container->setSingleton(MailerInterface::class, function () use ($app) {
            return $app->mailer;
        });

        $container->setSingleton(ContactService::class, [], [
            $app->params['adminEmail']
        ]);

        $container->setSingleton(ManagerInterface::class, function () use ($app) {
            return $app->authManager;
        });

        $container->set(Menu::class, \common\widgets\Menu::class);

        $container->set(Nav::class, \common\widgets\Nav::class);
    }
}