<?php


namespace common\components;


use core\widgets\FilterSelect2Widget;
use yii\grid\DataColumn;

class ProjectColumn extends DataColumn
{
    public $data;
    public $disabled = false;

    public $attribute = 'project_id';

    public $contentOptions = [
        'style' => 'width: 150px;'
    ];

    public function init()
    {
        parent::init();

        if ($this->filter === null) {
            $filterModel = $this->grid->filterModel;
            $this->filter = FilterSelect2Widget::widget([
                'filterModel' => $filterModel,
                'name' => $this->attribute,
                'data' => $this->data,
                'disabled' => $this->disabled,
            ]);
        }

        if ($this->value === null) {
            $this->value = function ($model) {
                return $model->getProject()->one()->name;
            };
        }
    }

}