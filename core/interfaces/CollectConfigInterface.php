<?php

namespace core\interfaces;

interface CollectConfigInterface
{
    public function getIncludeDirs();
    public function getExcludeDirs();
    public function getIncludeFiles();
    public function getExcludeFiles();
    public function export();
}