<?php


namespace common\components;


use yii\grid\DataColumn;

class PJColumn extends DataColumn
{
    public $attribute = 'property_json';

    public $contentOptions = [
        'style' => 'word-break:break-all;'
    ];

}