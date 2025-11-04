<?php

namespace core\helpers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\rbac\Item;

class RoleHelper
{
    public static function statusList(): array
    {
        return [
            Item::TYPE_ROLE => Yii::t('app','Role'),
            Item::TYPE_PERMISSION => Yii::t('app','Permission'),
        ];
    }

    public static function statusName($status): string
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    public static function statusLabel($status): string
    {
        switch ($status) {
            case Item::TYPE_ROLE:
                $class = 'label label-success';
                break;
            case Item::TYPE_PERMISSION:
                $class = 'label label-warning';
                break;
            default:
                $class = 'label label-default';
        }

        return Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), [
            'class' => $class,
        ]);
    }

    public static function rolesList(): array
    {
        $roles = \Yii::$app->authManager->getRoles();
        $permissions = \Yii::$app->authManager->getPermissions();
        $roles = array_merge($roles, $permissions);

        return ArrayHelper::map($roles, 'name', 'name');
    }
}