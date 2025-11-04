<?php

namespace core\dto;

use core\interfaces\CollectConfigInterface;

class DTOBasedConfigWriter implements CollectConfigInterface
{
    private $includeDirs = [];
    private $excludeDirs = [];
    private $includeFiles = [];
    private $excludeFiles = [];

    public function setIncludeDirs($dirs)
    {
        $this->includeDirs = (array)$dirs;
        return $this;
    }

    public function setExcludeDirs($dirs)
    {
        $this->excludeDirs = (array)$dirs;
        return $this;
    }

    public function setIncludeFiles($files)
    {
        $this->includeFiles = (array)$files;
        return $this;
    }

    public function setExcludeFiles($files)
    {
        $this->excludeFiles = (array)$files;
        return $this;
    }

    public function getIncludeDirs()
    {
        return $this->includeDirs;
    }

    public function getExcludeDirs()
    {
        return $this->excludeDirs;
    }

    public function getIncludeFiles()
    {
        return $this->includeFiles;
    }

    public function getExcludeFiles()
    {
        return $this->excludeFiles;
    }

    public function export()
    {
        return [
            'include' => [
                'dirs' => $this->includeDirs,
                'files' => $this->includeFiles,
            ],
            'exclude' => [
                'dirs' => $this->excludeDirs,
                'files' => $this->excludeFiles,
            ],
        ];
    }
}