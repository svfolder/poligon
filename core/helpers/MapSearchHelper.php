<?php


namespace core\helpers;

use Yii;
use yii\helpers\Inflector;
use core\dto\DTOClassInfo;

class MapSearchHelper extends BaseMapHelper
{

    /**
     * @var array|null
     */
    private static $_searchMap = null;

    public static function buildSearchMap(): array
    {
        $map = [];

        foreach (self::getDbTableNames() as $table) {
            $key = strtolower($table);
            if (!isset($map[$key])) {
                $dto = self::buildDTOForSearch($table);
                $map[$key] = $dto;
            }
        }

        return self::$_searchMap = $map;
    }

    public static function getSearchByTable(string $table): ?DTOClassInfo
    {
        if (self::$_searchMap === null) {
            self::buildSearchMap();
        }

        return self::$_searchMap[strtolower($table)] ?? null;
    }

    private static function buildDTOForSearch(string $table): DTOClassInfo
    {
        $baseDTO = self::buildDTOForEntity($table);
        return new DTOClassInfo(
            $baseDTO->className . 'Search',
            $baseDTO->namespace,
            $baseDTO->isKitEntity,
            $baseDTO->table
        );
    }
}