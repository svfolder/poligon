<?php


namespace core\helpers;


use core\entities\User\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class StatusConstantHelper
{


    public static function statusLabel($status_type): string
    {
        $class = "label label-{$status_type}";

        return Html::tag('span', $status_type, [
            'class' => $class,
        ]);
    }

}