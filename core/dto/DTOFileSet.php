<?php

namespace core\dto;

class DTOFileSet
{
    public $dirs;
    public $files;

    public function __construct()
    {
        $this->dirs = [];
        $this->files = [];
    }
}