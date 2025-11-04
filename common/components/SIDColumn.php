<?php


namespace common\components;


use yii\grid\DataColumn;

class SIDColumn extends DataColumn
{
    public $headerOptions = [
        'style' => 'width: 20px; text-align: center;'
    ];

    public $contentOptions = [
        'class' => 'text-center'
    ];

    public $attribute = 'id';

    public $label = 'UID';

}