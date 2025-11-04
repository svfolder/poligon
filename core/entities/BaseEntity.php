<?php

namespace core\entities;

use yii\db\SchemaBuilderTrait;
use yii\db\Connection;

class BaseEntity
{
    use SchemaBuilderTrait;

    protected $tableName;
    protected $columns = [];
    protected $relations = [];
    protected $compositePrimaryKey = [];

    public function __construct(string $tableName)
    {
        $this->tableName = $tableName;

        if (!$this->isCrossTable()) {
            $this->addColumn('id', $this->primaryKey());
        }
    }

    /**
     * @return string
     */
    public function getTableName(): string
    {
        return $this->tableName;
    }

    /**
     * @param string $name
     * @param mixed $type
     */
    public function addColumn(string $name, $type): void
    {
        $this->columns[$name] = $type;
    }

    /**
     * @param string $relatedTable
     * @param string $relationType
     * @param array $options
     */
    public function addRelation(string $relatedTable, string $relationType, array $options = []): void
    {
        $this->relations[] = [
            'relatedTable' => $relatedTable,
            'relationType' => $relationType,
            'options' => $options,
        ];
    }

    /**
     * @return array
     */
    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @return array
     */
    public function getRelations(): array
    {
        return $this->relations;
    }

    /**
     * @param array $columns
     */
    public function addCompositePrimaryKey(array $columns): void
    {
        $this->compositePrimaryKey = $columns;
    }

    /**
     * @return array
     */
    public function getCompositePrimaryKey(): array
    {
        return $this->compositePrimaryKey;
    }

    protected function isCrossTable(): bool
    {
        return strpos($this->tableName, '_assignment') !== false;
    }

    /**
     * @return Connection
     * @throws \yii\db\Exception
     */
    public function getDb(): Connection
    {
        $db = \Yii::$app->db;
        if ($db->getIsActive() === false) {
            $db->open();
        }
        return $db;
    }
}