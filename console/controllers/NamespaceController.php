<?php

namespace console\controllers;

use yii\console\Controller;
use Symfony\Component\Finder\Finder;

class NamespaceController extends Controller
{
    public function actionIndex()
    {
        // Путь к папке core
        $corePath = \Yii::getAlias('@core');

        // Инициализация Finder для поиска PHP-файлов
        $finder = new Finder();
        $finder->files()->in($corePath)->name('*.php');

        $namespaces = [];

        // Проход по всем найденным файлам
        foreach ($finder as $file) {
            $filePath = $file->getRealPath();
            $content = file_get_contents($filePath);

            // Извлечение неймспейса с помощью регулярного выражения
            if (preg_match('/namespace\s+([^\s;]+)\s*;/i', $content, $matches)) {
                $namespace = $matches[1];
                $namespaces[] = $namespace;
            }
        }

        // Удаление дубликатов
        $namespaces = array_unique($namespaces);

        // Вывод результатов
        echo "Found namespaces in the 'core' directory:\n";
        foreach ($namespaces as $namespace) {
            echo "- $namespace\n";
        }
    }
}