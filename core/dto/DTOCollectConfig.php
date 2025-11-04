<?php

namespace core\dto;

use core\interfaces\CollectConfigInterface;

class DTOCollectConfig implements CollectConfigInterface
{
    public $include;
    public $exclude;
    public $code_style;

    public function __construct()
    {
        $this->include = new DTOFileSet();
        $this->exclude = new DTOFileSet();
        $this->code_style = [
            'recommended' => new DTOFileSet(),
            'bad' => new DTOFileSet(),
            'forbidden' => new DTOFileSet(),
        ];
    }

    public function getIncludeDirs(): array
    {
        return $this->include->dirs;
    }

    public function getExcludeDirs(): array
    {
        return $this->exclude->dirs;
    }

    public function getIncludeFiles(): array
    {
        return $this->include->files;
    }

    public function getExcludeFiles(): array
    {
        return $this->exclude->files;
    }

    public function getCodeStyleRecommended(): array
    {
        return $this->code_style['recommended']->files;
    }

    public function getCodeStyleBad(): array
    {
        return $this->code_style['bad']->files;
    }

    public function getCodeStyleForbidden(): array
    {
        return $this->code_style['forbidden']->files;
    }

    public function export(): array
    {
        return [
            'include' => [
                'dirs' => $this->include->dirs,
                'files' => $this->include->files,
            ],
            'exclude' => [
                'dirs' => $this->exclude->dirs,
                'files' => $this->exclude->files,
            ],
            'code_style' => [
                'recommended' => $this->code_style['recommended']->files,
                'bad' => $this->code_style['bad']->files,
                'forbidden' => $this->code_style['forbidden']->files,
            ],
        ];
    }
}