<?php

namespace console\controllers;

use yii\console\Controller;
use yii\helpers\Console;

/**
 * Контроллер для обновления путей в меню с учетом новой структуры файлов
 *
 * Обновляет пути к файлам в меню навигации с учетом их перемещения в подпапки.
 */
class UpdateMenuPathsController extends Controller
{

    /**
     * @param string|null $directory
     * @return int
     */
    public function actionIndex(?string $directory = ''): int
    {
        $currentDir = \Yii::getAlias('@external') . "/{$directory}";
        
        $this->stdout("Начинаю обновление путей в меню в директории: {$currentDir}\n", Console::FG_YELLOW);

        $menuFile = $currentDir . '/partials/sidenav.php';
        
        if (!file_exists($menuFile)) {
            $this->stderr("Файл меню {$menuFile} не существует\n", Console::FG_RED);
            return 1;
        }

        // Читаем содержимое файла меню
        $content = file_get_contents($menuFile);
        
        if ($content === false) {
            $this->stderr("Не удалось прочитать файл меню {$menuFile}\n", Console::FG_RED);
            return 1;
        }

        // Найдем все ссылки на PHP файлы в меню
        // Ищем href="filename.php" или href='filename.php'
        $phpLinks = [];
        preg_match_all('/href="([^"]*\.php)"/', $content, $matches1);
        preg_match_all("/href='([^']*\.php)'/", $content, $matches2);
        
        $phpLinks = array_merge($matches1[1], $matches2[1]);
        
        // Уникальные имена файлов
        $uniqueFiles = array_unique($phpLinks);
        
        $this->stdout("Найдено уникальных файлов в меню: " . count($uniqueFiles) . "\n", Console::FG_YELLOW);

        // Для каждого файла проверим, существует ли он в component
        foreach ($uniqueFiles as $filename) {
            if (in_array($filename, ['index.php', 'landing.php'])) { // Файлы в корне оставляем без изменений
                continue;
            }
            
            // Проверим, существует ли файл в какой-либо подпапке component
            $componentDir = $currentDir . '/component';
            if (!is_dir($componentDir)) {
                $this->stderr("Директория component не существует: {$componentDir}\n", Console::FG_RED);
                continue;
            }

            $subdirs = array_filter(scandir($componentDir), function ($item) use ($componentDir) {
                return $item !== '.' && $item !== '..' && is_dir($componentDir . '/' . $item);
            });

            foreach ($subdirs as $subdir) {
                $filePath = $componentDir . '/' . $subdir . '/' . $filename;
                if (file_exists($filePath)) {
                    // Обновим путь в основном содержимом
                    $oldHref = 'href="' . $filename . '"';
                    $newHref = 'href="component/' . $subdir . '/' . $filename . '"';
                    $content = str_replace($oldHref, $newHref, $content);
                    
                    $oldHrefSingle = "href='" . $filename . "'";
                    $newHrefSingle = "href='component/" . $subdir . "/" . $filename . "'";
                    $content = str_replace($oldHrefSingle, $newHrefSingle, $content);
                    
                    $this->stdout("Обновлен путь для {$filename}: component/{$subdir}/{$filename}\n", Console::FG_GREEN);
                    break;
                }
            }
        }

        // Запишем обновленное содержимое обратно в файл
        $result = file_put_contents($menuFile, $content);
        
        if ($result === false) {
            $this->stderr("Не удалось записать файл меню {$menuFile}\n", Console::FG_RED);
            return 1;
        }

        $this->stdout("Обновление путей в меню завершено.\n", Console::FG_YELLOW);
        return 0;
    }

    public function getHelp(): string
    {
        return 'Контроллер для обновления путей в меню с учетом новой структуры файлов.';
    }

    public function getHelpSummary(): string
    {
        return 'Обновляет пути к файлам в меню навигации.';
    }
}