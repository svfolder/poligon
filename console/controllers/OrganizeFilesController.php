<?php

namespace console\controllers;

use Yii;
use yii\base\Exception;
use yii\console\Controller;
use yii\helpers\BaseConsole;
use yii\helpers\FileHelper;

/**
 * Контроллер для группировки файлов по префиксам
 *
 * Организует файлы в текущей директории по папкам на основе префикса до первого дефиса.
 * Файлы без дефиса остаются в корневой директории.
 */
class OrganizeFilesController extends Controller
{

    /**
     * @var array Список файлов/директорий для исключения из обработки
     */
    public array $exclude = [
        'assets',
        'partials',
        '.kilocode',
        '.git',
        '.gitignore'
    ];

    /**
     * @param string|null $directory
     * @return int
     * @throws Exception
     */
    public function actionIndex(?string $directory = ''): int
    {

        $currentDir = Yii::getAlias('@external') . "/{$directory}";
        $componentDir = $currentDir . "/component";
        
        $this->stdout("Начинаю организацию файлов в директории: {$currentDir}\n", BaseConsole::FG_YELLOW);

        $excludeItems = array_flip($this->exclude);

        if (!is_dir($currentDir)) {
            $this->stderr("Директория {$currentDir} не существует\n", BaseConsole::FG_RED);
            return 1;
        }

        // Создаем директорию component, если она не существует
        FileHelper::createDirectory($componentDir, 0755, true);

        $files = array_filter(FileHelper::findFiles($currentDir, ['only' => ['*.php'], 'recursive' => false]), function ($fullPath) use ($excludeItems, $currentDir) {
            $filename = basename($fullPath);
            return !isset($excludeItems[$filename]);
        });

        $files = array_map('basename', $files);

        foreach ($files as $filename) {
            $filePath = FileHelper::normalizePath(Yii::getAlias($currentDir . '/' . $filename));

            if (strpos($filename, '-') !== false) {

                $parts = explode('-', $filename, 2);
                $prefix = $parts[0];

                // Создаем директорию внутри component
                $targetDir = Yii::getAlias($componentDir . '/' . $prefix);
                FileHelper::createDirectory($targetDir, 0755, true);

                $targetPath = Yii::getAlias($targetDir . '/' . $filename);
                $result = rename($filePath, $targetPath);

                if ($result) {
                    $this->stdout("Перемещен файл {$filename} в директорию component/{$prefix}/\n", BaseConsole::FG_GREEN);
                } else {
                    $this->stderr("Ошибка при перемещении файла {$filename}\n", BaseConsole::FG_RED);
                }
            } else {
                $this->stdout("Файл {$filename} оставлен в корневой директории (без дефиса)\n", BaseConsole::FG_BLUE);
            }
        }

        $this->stdout("Организация файлов завершена.\n", BaseConsole::FG_YELLOW);
        return 0;
    }

    public function getHelp(): string
    {
        return 'Контроллер для группировки файлов по префиксам до первого дефиса.';
    }

    public function getHelpSummary(): string
    {
        return 'Организует файлы в папки на основе префикса в имени файла.';
    }
}