<?php


namespace core\helpers;


use core\dto\DTOClassInfo;
use Yii;

class MapQueryHelper extends BaseMapHelper
{

    /**
     * @var array|null
     */
    private static $_queryMap = null;

    public static function buildMap(): array
    {
        $map = [];

        foreach (self::getDbTableNames() as $table) {

            foreach (self::$_exclude_start as $item){
                if (str_starts_with($table, $item)){
                    continue 2;
                }
            }

            $key = strtolower($table);
            if (!isset($map[$key])) {
                $dto = self::buildDTOForQuery($table);
                $map[$key] = $dto;
            }
        }

        return self::$_queryMap = $map;
    }

    private static function buildDTOForQuery(string $table): DTOClassInfo
    {
        $baseDTO = self::buildDTOForEntity($table);
        return new DTOClassInfo(
            $baseDTO->className . 'Query',
            $baseDTO->namespace,
            $baseDTO->isKitEntity,
            $baseDTO->table
        );
    }

    public static function getQueryByTable(string $table): ?DTOClassInfo
    {
        if (self::$_queryMap === null) {
            self::buildMap();
        }

        return self::$_queryMap[strtolower($table)] ?? null;
    }

}