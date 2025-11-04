<?php


namespace core\widgets;

use yii\base\Model;


class FilterSelect2Widget extends Select2Widget
{
    /** @var Model */
    public $filterModel;

    protected function initOptions()
    {
        parent::initOptions();

        $this->options = array_merge([
            'placeholder' => 'Поиск ...',
        ], $this->options);

        if ($this->filterModel){
            $attribute = $this->name;
            $this->name = "{$this->filterModel->formName()}[{$attribute}]";

            if (isset($this->filterModel->{$attribute})){
                $this->value = $this->filterModel->{$attribute};
            }
        }
    }

}