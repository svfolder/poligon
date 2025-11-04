<?php

namespace core\helpers;

use core\dto\DTOClassInfo;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Yii;
use yii\helpers\FileHelper;

class EntityMapHelper
{
    /** @var null|array */
    private static $_map = null;

    /**
     * Получить DTOClassInfo по имени таблицы на основе существующих файлов сущностей
     */
    public static function getClassByTable(string $table): ?DTOClassInfo
    {
        if (!self::$_map) {
            self::$_map = self::buildClassMap(['Query', 'Search', 'SearchTrait']);
        }

        return self::$_map[strtolower($table)] ?? null;
    }

    /**
     * Построить карту классов
     */
    public static function buildClassMap(array $excludedSuffixes = []): array
    {
        $map = [];
        $basePath = FileHelper::normalizePath(Yii::getAlias('@core/entities'));
        $directories = glob(FileHelper::normalizePath("{$basePath}/*"), GLOB_ONLYDIR);

        foreach ($directories as $dir) {
            $dir = FileHelper::normalizePath($dir);
            $dirName = basename($dir);
            $isKitEntity = $dirName === 'Kit';

            foreach (self::scanDirForPhpFiles($dir) as $filePath) {
                $fileName = pathinfo($filePath, PATHINFO_FILENAME);

                foreach ($excludedSuffixes as $suffix) {
                    if (str_ends_with($fileName, $suffix)) {
                        continue 2;
                    }
                }

                $fullClassName = "core\\entities\\$dirName\\$fileName";

                try {

                    if (class_exists($fullClassName) && method_exists($fullClassName, 'tableName')){
                        $table = $fullClassName::tableName();

                        $table = \Yii::$app->getDb()->getSchema()->getRawTableName($table);

                        $map[strtolower($table)] = new DTOClassInfo(
                            $fileName,
                            "core\\entities\\$dirName",
                            $isKitEntity,
                            $table
                        );
                    }

                } catch (\Exception $e) {
                    continue;
                }


            }
        }

        return $map;
    }

    private static function scanDirForPhpFiles(string $dir): array
    {
        $files = [];
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));

        foreach ($iterator as $file) {
            if (!$file->isDir() && strtolower($file->getExtension()) === 'php') {
                $files[] = $file->getPathname();
            }
        }

        return $files;
    }
}