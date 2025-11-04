<?php


namespace core\helpers;


use core\dto\DTOClassInfo;

class MapHelper extends BaseMapHelper
{

    /**
     * @var array|null
     */
    private static $_map = null;

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
                $dto = self::buildDTOForEntity($table);
                $map[$key] = $dto;
            }
        }

        return self::$_map = $map;
    }

    public static function getEntityByTable(string $table): ?DTOClassInfo
    {
        if (self::$_map === null) {
            self::buildMap();
        }

        return self::$_map[strtolower($table)] ?? null;
    }
}