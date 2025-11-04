<?php

namespace core\services;

use core\entities\BaseEntity;
use yii\db\Exception;
use yii\db\TableSchema;

class TableService
{
    private $db;

    /**
     * @param \yii\db\Connection $db
     */
    public function __construct(\yii\db\Connection $db)
    {
        $this->db = $db;
    }

    /**
     * @param BaseEntity[] $entities
     * @throws Exception
     */
    public function createTables(array $entities): void
    {
        // Этап 1: Создание таблиц
        foreach ($entities as $entity) {
            $tableName = $entity->getTableName();

            if ($this->tableExists($tableName)) {
                echo "Table '$tableName' already exists.\n";
                continue;
            }

            $columns = $entity->getColumns();

            $this->db->createCommand()
                ->createTable($tableName, $columns)
                ->execute();

            $compositePrimaryKey = $entity->getCompositePrimaryKey();
            if (!empty($compositePrimaryKey)) {
                $this->db->createCommand()
                    ->addPrimaryKey("pk_{$tableName}", $tableName, $compositePrimaryKey)
                    ->execute();
            }
        }

        // Этап 2: Добавление внешних ключей
        foreach ($entities as $entity) {
            $tableName = $entity->getTableName();

            foreach ($entity->getRelations() as $relation) {
                $this->addForeignKey(
                    $tableName,
                    $relation['options']['foreignKey'],
                    $relation['relatedTable']
                );
            }
        }
    }

    /**
     * @param string $tableName
     * @return bool
     */
    private function tableExists(string $tableName): bool
    {
        try {
            $schema = $this->db->getSchema();
            return $schema->getTableSchema($tableName) !== null;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param string $tableName
     * @param string $foreignKey
     * @param string $relatedTable
     * @throws Exception
     */
    private function addForeignKey(string $tableName, string $foreignKey, string $relatedTable): void
    {
        // Убедитесь, что связанная таблица существует
        if (!$this->tableExists($relatedTable)) {
            throw new Exception("Related table '$relatedTable' does not exist.");
        }

        $this->db->createCommand()
            ->addForeignKey(
                "fk_{$tableName}_{$relatedTable}",
                $tableName,
                $foreignKey,
                $relatedTable,
                'id',
                'CASCADE'
            )
            ->execute();
    }
}