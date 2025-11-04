<?php


namespace core\helpers;

use Yii;
use yii\helpers\Inflector;
use core\dto\DTOClassInfo;


class BaseMapHelper
{
    protected static $_exclude_start = [
        '_',
        'auth_',
        'user_',
        'oauth_',
        'migration'
    ];

    protected static function getDbTableNames(): array
    {
        return Yii::$app->db->schema->getTableNames();
    }

    protected static function getKitDbTableNames(): array
    {
        return Yii::$app->kit->schema->getTableNames();
    }

    protected static function buildDTOForEntity(string $table): DTOClassInfo
    {
        $rawTable = Yii::$app->getDb()->getSchema()->getRawTableName($table);
        $className = Inflector::camelize(Inflector::singularize(
            str_replace('kit_', '', $rawTable)
        ));
        $dirName = self::determineDirectory($rawTable);

        return new DTOClassInfo(
            $className,
            "core\\entities\\$dirName",
            $dirName === 'Kit',
            $rawTable
        );
    }

    protected static function buildDTOForForm(string $table): DTOClassInfo
    {
        $rawTable = Yii::$app->getDb()->getSchema()->getRawTableName($table);
        $className = Inflector::camelize(Inflector::singularize($rawTable));
        $dirName = self::determineDirectory($rawTable);

        return new DTOClassInfo(
            $className,
            "core\\forms\\$dirName",
            $dirName === 'Kit',
            $rawTable
        );
    }

    protected static function determineDirectory(string $table): string
    {
        if (str_starts_with($table, 'kit_')) {
            return 'Kit';
        }

        // Здесь можно добавить правила подпапок по префиксу или домену
        $prefixes = [
            'auth_' => 'Auth',
            'menu_' => 'Menu',
            'user_' => 'User',
            'category_' => 'Category',
            'country_' => 'Country',
            'language_' => 'Language',
        ];

        foreach ($prefixes as $prefix => $dir) {
            if (str_starts_with($table, $prefix)) {
                return $dir;
            }
        }

        return $dir = Inflector::camelize(Inflector::singularize($table));
    }
}