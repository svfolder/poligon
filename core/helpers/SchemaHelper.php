<?php


namespace core\helpers;


use backend\components\Convertor;
use backend\components\dto\DTOVariable;
use Yii;
use yii\db\Connection;
use yii\db\TableSchema;
use yii\helpers\Inflector;

class SchemaHelper
{
    public static function getDb(string $tableName): Connection
    {
        if (str_starts_with($tableName, 'kit_')){
            return Yii::$app->kit;
        }else{
            return Yii::$app->db;
        }
    }

    public static function getSchemaByTable(string $tableName): TableSchema
    {
        return self::getDb($tableName)->getTableSchema($tableName);
    }

    public static function getPrimaryKeys(TableSchema $schema): array
    {
        $param = [];
        foreach ($schema->columns as $column) {
            if ($column->isPrimaryKey){
                $param[$column->name] = $column->name;
            }
        }
        return $param;
    }

    public static function getNotPrimaryKeys(TableSchema $schema): array
    {
        $param = [];
        foreach ($schema->columns as $column) {
            if (!$column->isPrimaryKey){
                $param[$column->name] = $column->name;
            }
        }
        return $param;
    }

    /**
     * @param TableSchema $schema
     * @param bool $flip
     * @return array
     */
    public static function getRelatedFields(TableSchema $schema, $flip = false): array
    {

        $related_list = [];
        if (sizeof($schema->foreignKeys) == 0 ){
            return [];
        }

        foreach ($schema->foreignKeys as $refs) {

            $refTable = $refs[0];
            unset($refs[0]);
            $fks = array_keys($refs);
            $field = $fks[0];

            $refTable = Inflector::id2camel($refTable, '_');
            $refClassName = Inflector::singularize($refTable);

            $related_list[$field] = $refClassName;
        }

        if ($flip){
            return array_flip($related_list);
        }else{
            return $related_list;
        }
    }

    public static function getAssignField(TableSchema $schema, string $assignObject): ?string
    {
        $assignRelatedFields = SchemaHelper::getRelatedFields($schema, true);
        return $assignRelatedFields[$assignObject];
    }

    public static function getExtendParamByAssignObject(TableSchema $schema, string $assignObject): string
    {
        $notPrimaryKeys = SchemaHelper::getNotPrimaryKeys($schema);
        $assignField = SchemaHelper::getAssignField($schema, $assignObject);

        $sequence = array_merge(
            [$assignField => $assignField],
            $notPrimaryKeys
        );

        foreach ($sequence as $item) {
            $sequence[$item] = "\$assignment->{$item}";
        }

        return Convertor::associfyByKey($sequence);
    }

    public static function getBindExtendParam(TableSchema $schema, string $entityVariable): string
    {
        $notPrimaryKeys = SchemaHelper::getNotPrimaryKeys($schema);

        $sequence = [];
        foreach ($notPrimaryKeys as $notPrimaryKey) {
            $sequence[] = new DTOVariable("object", $notPrimaryKey);
        }

        return Convertor::objectize(
            array_merge(
                [new DTOVariable($entityVariable, 'id')],
                $sequence
            )
        );
    }

}