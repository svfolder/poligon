<?php


namespace core\helpers;


use core\dto\DTOClassInfo;
use Yii;

class MapFormHelper extends BaseMapHelper
{

    /**
     * @var array|null
     */
    private static $_formMap = null;

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
                $map[$key] = self::buildMapForForm($table);
            }
        }

        return self::$_formMap = $map;
    }

    private static function buildMapForForm(string $table): DTOClassInfo
    {
        $baseDTO = self::buildDTOForForm($table);
        return new DTOClassInfo(
            $baseDTO->className . 'Form',
            $baseDTO->namespace,
            $baseDTO->isKitEntity,
            $baseDTO->table
        );
    }

    public static function getFormByTable(string $table): ?DTOClassInfo
    {
        if (self::$_formMap === null) {
            self::buildMap();
        }

        return self::$_formMap[strtolower($table)] ?? null;
    }

}