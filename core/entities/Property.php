<?php

namespace core\entities;

class Property
{
    public $genBatch;
    public $genTruncate;
    public $genImport;
    public $genReadRepository;
    public $addTransactionService;
    public $genExtendService;
    public $genExport;
    public $genCreateAjax;
    public $shortMode;
    public $genRememberFilter;

    public function __construct(
        $genBatch,
        $genTruncate,
        $genImport,
        $genReadRepository,
        $addTransactionService,
        $genExtendService,
        $genExport,
        $genCreateAjax,
        $shortMode,
        $genRememberFilter
    ) {
        $this->genBatch = (boolean)$genBatch;
        $this->genTruncate = (boolean)$genTruncate;
        $this->genImport = (boolean)$genImport;
        $this->genReadRepository = (boolean)$genReadRepository;
        $this->addTransactionService = (boolean)$addTransactionService;
        $this->genExtendService = (boolean)$genExtendService;
        $this->genExport = (boolean)$genExport;
        $this->genCreateAjax = (boolean)$genCreateAjax;
        $this->shortMode = (boolean)$shortMode;
        $this->genRememberFilter = (boolean)$genRememberFilter;
    }
}
