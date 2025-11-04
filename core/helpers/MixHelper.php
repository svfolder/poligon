<?php


namespace core\helpers;


use yii\helpers\Inflector;

class MixHelper
{

    public static function cleanEmptyKey($sequence): array
    {
        return array_filter($sequence, function ($key) {
            return $key !== null && $key !== '';
        }, ARRAY_FILTER_USE_KEY);
    }

    public static function getAssignmentVariable(string $assignment): string
    {
        return Inflector::variablize(Inflector::pluralize($assignment));
    }

}