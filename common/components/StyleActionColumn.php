<?php


namespace common\components;


use backend\widgets\grid\FilterContentActionColumn;
use Yii;

class StyleActionColumn extends FilterContentActionColumn
{
    public $contentOptions = ['style' => 'text-align: center; width: 130px;'];

    public $header = 'Действия';

    public $headerOptions = ['style' => 'text-align: center;'];

    protected function initDefaultButtons()
    {
        parent::initDefaultButtons();

        $this->buttonAdditionalOptions = [
            'view' => ['class' => 'btn btn-success btn-sm'],
            'update' => ['class' => 'btn btn-default btn-sm'],
            'delete' => ['class' => 'btn btn-danger btn-sm'],
        ];

    }

}