<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use yii\helpers\FileHelper;

class StructureController extends Controller
{
    private $excludedPatterns = [
        '/.git',
        '/vendor',
        '/frontend',
        '/api',
        '/node_modules',
        '/logs',
        '/tmp',
        '/.idea',
        '/backend/web',
        '/backend/gii',
        '/backend/components',
        '/backend/runtime',
        '/backend/tests',
        '/console/runtime',
        '/console/migrations',
        '/environments',
        '/doc',
        '/core/tests',
        '/common/tests',
        '/backend/controllers/kit',
        '/backend/views/kit',
        '/core/entities/Kit',
        '/core/forms/Kit',
        '/core/repositories/kit',
        '/core/services/kit',
    ];

    /**
     * Генерирует лаконичное описание структуры проекта с неймспейсами.
     */
    public function actionExport()
    {
        $projectPath = Yii::getAlias("@root");
        $structure = $this->buildProjectStructure($projectPath);

        // Сохраняем результат в файл
        $outputFile = Yii::getAlias("@root/structure.txt");
        file_put_contents($outputFile, $structure);

        $this->stdout("Project structure exported to {$outputFile}" . PHP_EOL);
    }

    /**
     * Рекурсивно строит описание структуры проекта с неймспейсами.
     *
     * @param string $path Путь к директории.
     * @return string Структура проекта.
     */
    private function buildProjectStructure(string $path): string
    {
        $structure = [];

        // Находим все папки
        $directories = FileHelper::findDirectories($path, [
            'except' => $this->excludedPatterns,
        ]);

        foreach ($directories as $dir) {
            $relativePath = str_replace($path, '', $dir);
            $relativePath = FileHelper::normalizePath($relativePath);

            if ($this->shouldExclude($relativePath)) {
                continue;
            }

            $structure[] = "\\" . trim($relativePath, '\\');

            // Находим все файлы внутри текущей папки
            $files = FileHelper::findFiles($dir, [
                'except' => $this->excludedPatterns,
            ]);

            foreach ($files as $file) {
                $fileRelativePath = str_replace($path, '', $file);
                $fileRelativePath = FileHelper::normalizePath($fileRelativePath);

                // Добавляем проверку shouldExclude для каждого файла
                if ($this->shouldExclude($fileRelativePath)) {
                    continue;
                }

                $structure[] = $fileRelativePath;
            }
        }

        // Обрабатываем файлы в корневой директории
        $rootFiles = $this->processRootFiles($path);
        foreach ($rootFiles as $file) {
            $structure[] = $file;
        }

        return "STRUCTURE:\n" . implode("\n", $structure) . "\n\nNAMESPACES:\n";
    }

    /**
     * Обрабатывает файлы в корневой директории.
     *
     * @param string $path Путь к корневой директории.
     * @return array Массив файлов в корневой директории.
     */
    private function processRootFiles(string $path): array
    {
        $rootFiles = [];
        $files = FileHelper::findFiles($path, [
            'only' => ['*'], // Ищем только файлы
            'except' => $this->excludedPatterns,
            'recursive' => false, // Отключаем рекурсию
        ]);

        foreach ($files as $file) {
            $relativePath = str_replace($path, '', $file);
            $relativePath = FileHelper::normalizePath($relativePath);

            // Убираем начальный слеш для всех файлов в корне
            $relativePath = ltrim($relativePath, '/\\');

            $rootFiles[] = $relativePath;
        }

        return $rootFiles;
    }

    /**
     * Проверяет, нужно ли исключить путь из структуры.
     *
     * @param string $path Относительный путь.
     * @return bool True, если путь нужно исключить.
     */
    private function shouldExclude(string $path): bool
    {
        $normalizedPath = FileHelper::normalizePath($path);

        foreach ($this->excludedPatterns as $pattern) {
            $normalizedPattern = FileHelper::normalizePath($pattern);

            if (strpos($normalizedPath, $normalizedPattern) === 0) {
                return true;
            }
        }

        return false;
    }
}