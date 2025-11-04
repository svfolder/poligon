<?php

namespace core\services;

use core\entities\BaseEntity;
use core\helpers\RelationHelper;

class RelationBuilder
{
    /**
     * @param BaseEntity $parentTable
     * @param BaseEntity $childTable
     * @param string $foreignKeyName
     */
    public static function createOneToMany(BaseEntity $parentTable, BaseEntity $childTable, string $foreignKeyName): void
    {
        $childTable->addColumn($foreignKeyName, $childTable->integer()->notNull());
        $parentTable->addRelation($childTable->getTableName(), 'hasOne', ['foreignKey' => $foreignKeyName]);
    }

    /**
     * @param BaseEntity $table1
     * @param BaseEntity $table2
     * @return BaseEntity
     */
    public static function createManyToMany(BaseEntity $table1, BaseEntity $table2): BaseEntity
    {
        $crossTableName = RelationHelper::generateCrossTableName($table1->getTableName(), $table2->getTableName());
        $crossTable = new BaseEntity($crossTableName);

        $crossTable->addColumn($table1->getTableName() . '_id', $crossTable->integer()->notNull());
        $crossTable->addColumn($table2->getTableName() . '_id', $crossTable->integer()->notNull());
        $crossTable->addCompositePrimaryKey([$table1->getTableName() . '_id', $table2->getTableName() . '_id']);

        $table1->addRelation($table2->getTableName(), 'hasOne', ['foreignKey' => $table1->getTableName() . '_id']);
        $table2->addRelation($table1->getTableName(), 'hasOne', ['foreignKey' => $table2->getTableName() . '_id']);

        return $crossTable;
    }
}