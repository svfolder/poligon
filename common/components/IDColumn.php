<?php


namespace common\components;


use yii\grid\DataColumn;

class IDColumn extends DataColumn
{
    public $headerOptions = [
        'style' => 'width: 110px;'
    ];

    public $contentOptions = [
        'class' => 'text-center'
    ];

    public $attribute = 'id';

}