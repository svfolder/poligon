<?php


namespace core\dto;

class DTOClassInfo
{
    /** @var string */
    public $className;

    /** @var string */
    public $namespace;

    /** @var string */
    public $fullClassName;

    /** @var bool */
    public $isKitEntity;

    /** @var string */
    public $table;

    /**
     * DTOClassInfo constructor.
     *
     * @param string $className
     * @param string $namespace
     * @param bool $isKitEntity
     * @param string $table
     */
    public function __construct(string $className, string $namespace, $isKitEntity = false, string $table = '')
    {
        $this->className = $className;
        $this->namespace = $namespace;
        $this->fullClassName = $namespace . '\\' . $className;
        $this->isKitEntity = (bool)$isKitEntity;
        $this->table = $table;
    }

    public function getNamespaceOLD(): string
    {
        return "\\{$this->namespace}\\";
    }
}