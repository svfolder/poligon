<?php

namespace core\helpers;

use ReflectionClass;
use ReflectionException;
use Symfony\Component\Yaml\Yaml;
use yii\helpers\FileHelper;

class CodeHelper
{
    /**
     * Извлекает namespace из содержимого PHP-файла.
     *
     * @param string $content Содержимое файла.
     * @return string|null Извлечённый namespace или null.
     */
    public static function extractNamespace($content): ?string
    {
        if (preg_match('/namespace\s+([^\s;]+)\s*;/i', $content, $matches)) {
            return trim($matches[1]);
        }
        return null;
    }

    /**
     * Извлекает список use-директив из содержимого PHP-файла.
     *
     * @param string $content Содержимое файла.
     * @return array Массив имён классов из use.
     */
    public static function extractUses($content): array
    {
        preg_match_all('/use\s+([^\s;]+)\s*;/i', $content, $matches);
        return array_map('trim', $matches[1] ?? []);
    }

    /**
     * Извлекает имя класса из содержимого PHP-файла.
     *
     * @param string $content Содержимое файла.
     * @return string|null Имя класса или null.
     */
    public static function extractClassName($content): ?string
    {
        if (preg_match('/class\s+(\w+)/i', $content, $match)) {
            return $match[1];
        }
        return null;
    }

    /**
     * Получает информацию о классе через Reflection.
     *
     * @param string $fullClassName Полное имя класса.
     * @param string $content Содержимое файла.
     * @return array|null Информация о классе или null.
     * @throws ReflectionException
     */
    public static function getClassInfo($fullClassName, $content): ?array
    {
        if (class_exists($fullClassName)) {
            $reflector = new ReflectionClass($fullClassName);
        } else {
            $tmpFile = tempnam(sys_get_temp_dir(), 'php');
            file_put_contents($tmpFile, $content);

            try {
                include_once $tmpFile;
                if (!class_exists($fullClassName)) {
                    return null;
                }
                $reflector = new ReflectionClass($fullClassName);
            } catch (\Exception $e) {
                return null;
            } finally {
                @unlink($tmpFile);
            }
        }

        $parents = $reflector->getParentClass()
            ? [$reflector->getParentClass()->getName()]
            : [];

        $interfaces = $reflector->getInterfaceNames();
        $traits = $reflector->getTraitNames();
        $uses = self::extractUses($content);
        $dependencies = array_unique(array_merge($uses, $parents, $interfaces, $traits));

        return [
            'name' => $reflector->getShortName(),
            'namespace' => $reflector->getNamespaceName(),
            'full_name' => $fullClassName,
            'extends' => $parents,
            'implements' => $interfaces,
            'traits' => $traits,
            'uses' => $uses,
            'dependencies' => array_values($dependencies),
            'content' => $content,
        ];
    }

    /**
     * Сохраняет структуру проекта в YAML-файл.
     *
     * @param array $structure Структура проекта.
     * @param string $basePath Базовый путь.
     * @param string $outputFile Путь к выходному файлу.
     * @return void
     */
    public static function saveToYaml($structure, $basePath, $outputFile)
    {
        $data = [
            'project_root' => basename($basePath),
            'structure' => $structure,
        ];

        $yamlContent = Yaml::dump($data, 10, 4, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK);
        file_put_contents($outputFile, $yamlContent);
    }

    /**
     * Добавляет файл в структуру по частям пути.
     *
     * @param array &$structure Ссылка на структуру.
     * @param array $parts Части пути.
     * @param string $filename Имя файла.
     * @param array $fileData Данные файла.
     * @return void
     */
    public static function addToStructure(&$structure, $parts, $filename, $fileData)
    {
        $current = &$structure;
        foreach ($parts as $part) {
            if (!isset($current[$part])) {
                $current[$part] = [];
            }
            $current = &$current[$part];
        }
        $current[$filename] = $fileData;
    }

    /**
     * Загружает примеры кода для стилей.
     *
     * @param array $files Список путей к файлам.
     * @return array Ассоциативный массив: путь => содержимое.
     */
    public static function loadCodeStyleExamples($files): array
    {
        $result = [];
        foreach ($files as $file) {
            $path = \Yii::getAlias($file);
            if (file_exists($path)) {
                $result[$file] = file_get_contents($path);
            }
        }
        return $result;
    }
}