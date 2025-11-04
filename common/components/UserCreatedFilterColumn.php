<?php


namespace common\components;


use kartik\date\DatePicker;
use yii\grid\DataColumn;

class UserCreatedFilterColumn extends DataColumn
{
    public $attribute = 'created_at';
    public $searchModel;

    public function __construct($config = [])
    {
        $config['filter'] = DatePicker::widget([
            'model' => $config['searchModel'],
            'attribute' => 'date_from',
            'attribute2' => 'date_to',
            'type' => DatePicker::TYPE_RANGE,
            'separator' => '-',
            'pluginOptions' => [
                'todayHighlight' => true,
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
            ],
        ]);

        $config['format'] = 'datetime';

        parent::__construct($config);
    }

}