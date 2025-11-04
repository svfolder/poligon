<?php

namespace common\components;

use yii\helpers\Html;

/**
 * Class GridView
 * @package common\components
 */
class GridView extends \yii\grid\GridView
{

    public $layout = "{summary}\n{items}\n<div class=\"row grid-page-size\"><div class=\"col-sm-6\">{pager}</div><div class=\"col-sm-6 text-right\">{size}</div></div>";

    /**
     * Переключение количества записей на странице
     * @var array
     */
    public $size = [10, 20, 50, 100];

    /**
     *
     */
    public function init()
    {
        parent::init();

        $this->registerJs();
        $this->initPageSize();
    }

    private function registerJs()
    {

        $script = <<<SCRIPT

jQuery(function () {
    $('.grid-page-size select[name=page_size]').change(function () {
        document.cookie = 'page_size=' + $(this).val();
        location.reload();
    });
});

SCRIPT;

        $this->getView()->registerJs($script);
    }

    /**
     * Установка количества записей на странице
     */
    public function initPageSize()
    {
        $pageSize = isset($_COOKIE['page_size']) ? (int)$_COOKIE['page_size'] : 20;
        if (!in_array($pageSize, $this->size)) {
            $pageSize = 20;
        }

        $this->dataProvider->pagination->pageSize = $pageSize;
    }

    /**
     * Количество страниц на странице
     * @return string
     */
    public function renderSize()
    {
        $list = [];
        foreach ($this->size as $size) {
            $list[$size] = $size;
        }

        return 'Показать по: ' . Html::dropDownList('page_size', $this->dataProvider->pagination->pageSize, $list);
    }

    /**
     * Renders a section of the specified name.
     * If the named section is not supported, false will be returned.
     * @param string $name the section name, e.g., `{summary}`, `{items}`.
     * @return string|bool the rendering result of the section, or false if the named section is not supported.
     */
    public function renderSection($name)
    {
        if ($name == '{size}') {
            return $this->renderSize();
        } else {
            return parent::renderSection($name);
        }
    }

}
