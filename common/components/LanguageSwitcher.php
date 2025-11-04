<?php

namespace common\components;

use yii\helpers\Html;
use yii\helpers\Url;

class LanguageSwitcher extends \kmergen\LanguageSwitcher
{
    private array $flagMap = [
        'en' => 'gb',
        'ru' => 'ru',
        'es' => 'es',
        'de' => 'de',
        'fr' => 'fr',
        'it' => 'it',
        'pt' => 'pt',
        'pl' => 'pl',
        'uk' => 'ua',
        'ro' => 'ro',
    ];

    public function init()
    {
        parent::init();

        $this->parentTemplate = '<nav class="navbar-nav nav"><li class="dropdown">{activeItem}<ul class="dropdown-menu">{items}</ul></li></nav>';
        $this->activeItemTemplate = '<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="flag-icon flag-icon-{language}"></i> {label} <span class="caret"></span></a>';
        $this->itemTemplate = '<li><a href="{url}"><i class="flag-icon flag-icon-{language}"></i> {label}</a></li>';
    }

    protected function renderItem($language, $template): string
    {
        $langCode = substr($language['code'], 0, 2);
        $flagCode = $this->flagMap[$langCode] ?? $langCode;

        $replacements = [
            '{url}' => Url::to($language['url']),
            '{label}' => Html::encode($language['label']),
            '{language}' => $flagCode,
        ];

        return strtr($template, $replacements);
    }
}