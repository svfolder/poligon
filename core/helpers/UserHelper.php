<?php

namespace core\helpers;

use core\entities\User\User;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class UserHelper
{
    public static function statusList(): array
    {
        return [
            User::STATUS_BANNED => Yii::t('app','Is Banned'),
            User::STATUS_WAIT => Yii::t('app','Wait'),
            User::STATUS_ACTIVE => Yii::t('app','Active'),
        ];
    }

    public static function statusName($status): string
    {
        return ArrayHelper::getValue(self::statusList(), $status);
    }

    public static function statusLabel($status): string
    {
        switch ($status) {
            case User::STATUS_BANNED:
                $class = 'label label-danger';
                break;
            case User::STATUS_WAIT:
                $class = 'label label-default';
                break;
            case User::STATUS_ACTIVE:
                $class = 'label label-success';
                break;
            default:
                $class = 'label label-default';
        }

        return Html::tag('span', ArrayHelper::getValue(self::statusList(), $status), [
            'class' => $class,
        ]);
    }

    public static function bannedList(): array
    {
        return [
            User::BANNED_NO => Yii::t('app','Unbanned'),
            User::BANNED_YES => Yii::t('app','Banned'),
        ];
    }

    public static function rolesList(): array
    {
        return ArrayHelper::map(\Yii::$app->authManager->getRoles(), 'name', 'description');
    }
}