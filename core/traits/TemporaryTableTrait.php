<?php


namespace core\traits;


use Yii;
use yii\db\Exception;

trait TemporaryTableTrait
{

    public $tableName;
    public $indexPrefix = '';
    public $indexSuffix = '';

    /**
     * @param string $tableName
     * @param string $sql
     * @return int
     * @throws Exception
     */
    public function createTemporaryTable(string $tableName, string $sql): int
    {
        $sql = "CREATE TEMPORARY TABLE {$tableName} {$sql}";

        return Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * @throws Exception
     */
    public function dropTemporaryTable(string $tableName): int
    {
        $sql = "DROP TEMPORARY TABLE {$tableName}";
        return Yii::$app->db->createCommand($sql)->execute();
    }

    /**
     * @throws Exception
     */
    public function createIndex(string $name, string $tableName, $columns = [], $unique = false): void
    {
        if (isset($this->indexPrefix)){
            $name = $this->indexPrefix . $name;
        }

        if (isset($this->indexSuffix)){
            $name = $name . $this->indexSuffix;
        }

        Yii::$app->db->createCommand()->createIndex($name, $tableName, $columns, $unique)->execute();
    }

}