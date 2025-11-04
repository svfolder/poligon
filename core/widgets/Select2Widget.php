<?php


namespace core\widgets;


use kartik\select2\Select2;

class Select2Widget extends Select2
{
    public $theme = Select2::THEME_DEFAULT;

    public function init()
    {
        $this->defaultPluginOptions['placeholder'] = 'Выбрать из списка';

        parent::init();
        $this->initOptions();
    }

    protected function initOptions()
    {
        $this->pluginOptions = array_merge([
            'allowClear' => true,
            'multiple' => false,
        ], $this->pluginOptions);

        $this->options = array_merge([
        ], $this->options);
    }
}