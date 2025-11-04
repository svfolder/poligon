<?php


namespace core\traits;

use yii\helpers\Json;

trait ModelTrait
{

    public function plainErrors(): string
    {
        return implode("\n", $this->firstErrors);
    }

    public static function clearAutoIncrement()
    {
        self::deleteAll();
        $table = self::tableName();
        self::getDb()
            ->createCommand("ALTER TABLE `{$table}` AUTO_INCREMENT=0")
            ->execute();

        $behavior = self::instance()->getBehavior('nested');

        if ($behavior) {
            $root = new static();
            $root->name = 'Root';
            $root->makeRoot();
            $root->save(false);
        }
    }

    public static function listDatabases()
    {
        return self::getDb()
            ->createCommand("SHOW DATABASES WHERE `Database` NOT IN ('information_schema', 'mysql', 'performance_schema')")
            ->queryColumn();
    }

    public function encode($value): string
    {
        if ($value === null){$value = "";}

        if (!is_string($value)){
            return Json::encode($value);
        }else{
            if (json_decode($value) === false){
                return Json::encode($value);
            }else{
                return $value;
            }
        }
    }

}