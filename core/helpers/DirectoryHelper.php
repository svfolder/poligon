<?php


namespace core\helpers;


use core\repositories\kit\EntityRepository;
use core\repositories\kit\ProjectRepository;
use core\services\kit\EntityService;
use core\services\kit\ProjectService;
use yii\helpers\Inflector;

class DirectoryHelper
{

    /**
     * @var null|array
     */
    private static ?array $_entityDirectoryMap = null;

    /**
     * @var null|array
     */
    private static ?array $_entityDirectoryKitMap = null;

    public static function buildMap() : ?array
    {
        $entityDirectory = self::getEntityService()->getEntityBuffer();

        foreach ($entityDirectory as $table => $directory){
            $class = Inflector::classify($table);

            self::$_entityDirectoryMap[$class] = $directory;
            self::$_entityDirectoryMap["{$class}Query"] = $directory;
        }

        return self::$_entityDirectoryMap;
    }

    public static function buildKitMap() : ?array
    {
        $entityDirectory = self::getEntityService()->getEntityBuffer();

        foreach ($entityDirectory as $table => $directory){
            $class = Inflector::classify($table);

            if ($directory == 'Kit'){
                self::$_entityDirectoryKitMap[$class] = $directory;
                self::$_entityDirectoryKitMap["{$class}Query"] = $directory;
            }
        }

        return self::$_entityDirectoryKitMap;
    }


    public static function getSubDirectory($modelClass, bool $closed = false, bool $lower = false): string
    {
        if ($lower){
            return strtolower(self::getDirectory($modelClass, $closed));
        }else{
            return self::getDirectory($modelClass, $closed);
        }
    }

    public static function getDirectory($class, $closed = false, $isKit = false): string
    {
        if (self::$_entityDirectoryMap === null) {
            self::buildMap();
        }

        if (self::$_entityDirectoryKitMap === null) {
            self::buildKitMap();
        }

        if ($isKit){

            if (!str_starts_with($class, 'Kit')){
                $class = "Kit{$class}";
            }

            if (@array_key_exists($class, self::$_entityDirectoryKitMap)){
                if ($closed){
                    return self::$_entityDirectoryKitMap[$class] . '\\';
                }else{
                    return self::$_entityDirectoryKitMap[$class];
                }
            }

        }else{

            if (@array_key_exists($class, self::$_entityDirectoryMap)){
                if ($closed){
                    return self::$_entityDirectoryMap[$class] . '\\';
                }else{
                    return self::$_entityDirectoryMap[$class];
                }
            }
        }
        return '';
    }


    private static function getEntityService(): EntityService
    {
        return new EntityService(
            new EntityRepository(),
            new ProjectService(
                new ProjectRepository()
            ));
    }

}