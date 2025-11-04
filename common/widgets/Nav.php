<?php


namespace common\widgets;


class Nav extends \yii\bootstrap\Nav
{

    public function renderItem($item): string
    {
        $this->addIcon($item, 2);
        return parent::renderItem($item);
    }

    protected function renderDropdown($items, $parentItem): string
    {
        $result = [];
        foreach ($items as $item){
            $this->addIcon($item);
            $result[] = $item;
        }

        return parent::renderDropdown($result, $parentItem);
    }

    public function addIcon(&$item, $times = 0)
    {
        if (isset($item['label']) && isset($item['icon']) ){
            $icon = $item['icon'];
            $label = $item['label'];
            $space = str_repeat("&nbsp;", $times);
            $item['label'] = "<i class=\"{$icon}\"></i>{$space}{$label}";
        }
    }
}