<?php


namespace core\helpers;


use core\entities\Kit\EntityFieldGroup;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use Yii;
use yii\db\Connection;
use yii\db\Exception;
use yii\db\TableSchema;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\helpers\Inflector;
use yii\helpers\StringHelper;
use function nspl\a\filter;
use function nspl\a\any;
use function nspl\a\last;

class KitHelper
{
    /**
     * @var Connection
     */
    private static $db;

    /**
     * @var TableSchema
     */
    private static $schema;

    /**
     * @var string[]
     */
    private static $tables;

    /**
     * @param $excluded string[]
     * @return array|string[]
     */
    public static function tableList(array $excluded): array
    {
        self::$db = Yii::$app->db;
        self::$schema = self::$db->schema;
        self::$tables = self::$schema->getTableNames();

        $result = [];
        foreach (self::$tables as $table){
            if(array_key_exists($table, $excluded)){
                continue;
            }
            $result[$table] = $table;
        }

        if (env('HOST') == 'sv5kit.open'){
            $kitTableNames = Yii::$app->kit->schema->getTableNames();
            foreach ($kitTableNames as $table){
                if(array_key_exists($table, $excluded)){
                    continue;
                }
                $result[$table] = $table;
            }
        }

        return $result;
    }

    /**
     * @param $excluded string[]
     * @return array
     */
    public static function entityList(array $excluded): array
    {
        $result = [];
        foreach (self::tableList($excluded) as $tableName){
            $className = Inflector::classify($tableName);
            if (StringHelper::startsWith($className, 'Kit')){
                $className = str_replace('Kit', '', $className);
            }

            $result[$className] = $className;
        }

        return $result;
    }

    public static function simpleEntityList(): array
    {
        return self::filteredEntityList(
            ['Assignment'],
            false
        );
    }

    public static function assignmentEntityList(): array
    {
        return self::filteredEntityList(
            ['Assignment'],
            true
        );
    }


    public static function filteredEntityList(array $suffixes, bool $include = true, array $excluded_tables = []): array
    {
        return filter(
            function ($className) use ($suffixes, $include) {
                $match = any(
                    $suffixes,
                    function ($suffix) use ($className) {
                        return StringHelper::endsWith($className, $suffix);
                    }
                );

                return $include ? $match : !$match;
            },
            self::entityList($excluded_tables)
        );
    }

    /**
     * @return array
     */
    public static function entitySubdirectoryList() : array
    {
        $root = Yii::getAlias('@core/entities');
        $result = [];
        foreach (FileHelper::findDirectories($root) as $directory){
            $directory = str_replace(['\\', '/'], '', str_replace($root, '', $directory));
            $result[$directory] = $directory;
        }

        return $result;
    }

    /**
     * @return array
     */
    public static function controllerSubdirectoryList() : array
    {
        $root = Yii::getAlias('@backend/controllers');
        $result = [];
        foreach (FileHelper::findDirectories($root) as $directory){
            $directory = str_replace(['\\', '/'], '', str_replace($root, '', $directory));
            $result[$directory] = $directory;
        }

        return $result;
    }

    /**
     * @param string|array $mix
     * @param string[] $prefix
     */
    public static function cleanKitPrefix(&$mix, $prefix = ['kit_', 'Kit'])
    {
        if ( gettype($mix) == 'string' ){
            $mix = str_replace($prefix, '', $mix);
        }elseif (gettype($mix) == 'array'){

            $result = [];
            foreach ($mix as $key => $value) {

                if (gettype($value) == 'string'){
                    $result[$key] = str_replace($prefix, '', $value);
                }elseif (gettype($value) == 'array'){

                    foreach ($value as $key1 => $value1) {

                        if (gettype($value1) == 'string'){
                            $result[$key][$key1] = str_replace($prefix, '', $value1);
                        }elseif (gettype($value1) == 'boolean'){
                            $result[$key][$key1] = $value1;
                        }

                    }

                }

            }

            $mix = $result;
        }
    }

    /**
     * @param array $excluded
     * @return array
     * @throws Exception
     */
    public static function databaseList(array $excluded = []): array
    {
        self::$db = Yii::$app->db;

        $databaseList = self::$db
            ->createCommand("SHOW DATABASES WHERE `Database` NOT IN ('information_schema', 'mysql', 'performance_schema')")
            ->queryColumn();

        $result = [];
        foreach ($databaseList as $database){
            if(array_key_exists($database, array_flip($excluded))){
                continue;
            }
            $result[$database] = $database;
        }

        return $result;
    }

    /**
     * @return array
     */
    public static function entityGroupList(): array
    {
        return ArrayHelper::map(EntityFieldGroup::find()->all(), 'name', 'name');
    }

    public static function findClassFileByDirectories($path, $class, array $excludes = []): ?string
    {
        $path = FileHelper::normalizePath($path);
        $files = KitHelper::getFilesByDirectory($path, $excludes);

        if (array_key_exists("{$class}.php", $files)){
            $fullPath = $files["{$class}.php"];
            return str_replace( dirname(dirname($path)), '', FileHelper::normalizePath($fullPath));
        }
        return null;
    }

    /**
     * @param $path
     * @param array $excludes
     * @return array
     */
    public static function getFilesByDirectory($path, array $excludes = []): array
    {
        $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));

        $files = [];
        foreach ($rii as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getPath();
                $isExcluded = false;

                foreach ($excludes as $excludeDir) {
                    $excludeDir = FileHelper::normalizePath($excludeDir);
                    if (strpos($filePath, $excludeDir) !== false) {
                        $isExcluded = true;
                        break;
                    }
                }

                if (!$isExcluded) {
                    $files[$file->getBasename()] = $file->getPathname();
                }
            }
        }

        return $files;
    }

    public function getAllFilesByDirectory($path): array
    {
        $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path));

        $files = array();

        /** @var SplFileInfo $file */
        foreach ($rii as $file)
            if (!$file->isDir())
                $files[$file->getBasename()] = $file->getPathname();

        return $files;
    }

    public static function normalizeClass(string $class): string
    {
        $parts = explode('\\', $class);

        $className = array_pop($parts);

        if (str_starts_with($className, 'Kit')) {
            $className = substr($className, 3);
        }

        return implode('\\', array_merge($parts, [$className]));
    }

    public static function detectKit($modelClass): bool
    {

        $directory = last(
            explode('\\',
                str_replace(
                    '\\' . StringHelper::basename($modelClass), '', $modelClass
                )
            ));

        if ($directory == 'Kit'){
            return true;
        }

        return false;
    }

    public static function cleanKit(?string $class)
    {
        return str_replace('Kit', '', $class);
    }
}