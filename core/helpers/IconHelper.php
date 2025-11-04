<?php


namespace core\helpers;


use yii\helpers\Html;

class IconHelper
{

    public static function icon($class): string
    {
        $class = "fa-3x {$class}";

        return Html::tag('i', '', [
            'class' => $class,
        ]);
    }

}