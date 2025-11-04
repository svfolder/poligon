<?php

namespace console\controllers;

use yii\console\Controller;
use phpDocumentor\Reflection\DocBlockFactory;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;

class DocController extends Controller
{
    /**
     * Генерирует Markdown документацию для указанного класса.
     *
     * @param string $class Полное имя класса (например, 'core\entities\Category\Category')
     */
    public function actionGenerate($class)
    {
        // Преобразуем полное имя класса в путь к файлу
        $filePath = \Yii::getAlias('@' . str_replace('\\', '/', $class) . '.php');

        if (!file_exists($filePath)) {
            $this->stderr("Файл класса $filePath не найден.\n", \yii\helpers\Console::FG_RED);
            return 1;
        }

        // Инициализируем фабрику для анализа docblock
        $docBlockFactory = DocBlockFactory::createInstance();

        // Получаем ReflectionClass для класса
        try {
            $reflectionClass = new ReflectionClass($class);
        } catch (\Exception $e) {
            $this->stderr("Ошибка при анализе класса: " . $e->getMessage() . "\n", \yii\helpers\Console::FG_RED);
            return 1;
        }

        // Генерируем Markdown-документацию
        try {
            $markdown = $this->generateMarkdown($docBlockFactory, $reflectionClass);
            $this->stdout($markdown . "\n");
        } catch (\Exception $e) {
            $this->stderr("Ошибка при генерации документации: " . $e->getMessage() . "\n", \yii\helpers\Console::FG_RED);
            return 1;
        }

        return 0;
    }

    /**
     * Генерирует Markdown-документацию.
     *
     * @param \phpDocumentor\Reflection\DocBlockFactory $docBlockFactory Фабрика для анализа docblock
     * @param \ReflectionClass $reflectionClass ReflectionClass для класса
     * @return string Markdown-документация
     */
    private function generateMarkdown($docBlockFactory, $reflectionClass): string
    {
        $markdown = "# Класс `" . $reflectionClass->getName() . "`\n\n";

        // Добавляем описание класса
        if ($reflectionClass->getDocComment()) {
            try {
                $docBlock = $docBlockFactory->create($reflectionClass->getDocComment());
                $summary = $docBlock->getSummary();
                $description = $docBlock->getDescription()->render();

                $markdown .= "## Описание класса\n\n";
                if (!empty($summary)) {
                    $markdown .= "- **Краткое описание**: $summary\n";
                }
                if (!empty($description)) {
                    $markdown .= "- **Подробное описание**: $description\n";
                }
                $markdown .= $this->formatTags($docBlock->getTags());
            } catch (\Exception $e) {
                $markdown .= "- **Описание**: Не удалось разобрать комментарий.\n";
            }
        } else {
            $markdown .= "## Описание класса\n\n";
            $markdown .= "- **Описание**: Отсутствует.\n";
        }

        // Добавляем описание свойств
        $properties = $reflectionClass->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);
        foreach ($properties as $property) {
            if ($property->class === $reflectionClass->getName() && $property->getDocComment()) {
                try {
                    $docBlock = $docBlockFactory->create($property->getDocComment());
                    $summary = $docBlock->getSummary();
                    $description = $docBlock->getDescription()->render();

                    $markdown .= "### Свойство `" . $property->getName() . "`\n\n";
                    if (!empty($summary)) {
                        $markdown .= "- **Краткое описание**: $summary\n";
                    }
                    if (!empty($description)) {
                        $markdown .= "- **Подробное описание**: $description\n";
                    }
                    $markdown .= $this->formatTags($docBlock->getTags());
                } catch (\Exception $e) {
                    $markdown .= "- **Описание**: Не удалось разобрать комментарий.\n";
                }
            }
        }

        // Добавляем описание методов
        $methods = $reflectionClass->getMethods(ReflectionMethod::IS_PUBLIC | ReflectionMethod::IS_PROTECTED);
        foreach ($methods as $method) {
            // Включаем только методы, определенные в текущем классе
            if ($method->class === $reflectionClass->getName() && $method->getDocComment()) {
                try {
                    $docBlock = $docBlockFactory->create($method->getDocComment());
                    $summary = $docBlock->getSummary();
                    $description = $docBlock->getDescription()->render();

                    $markdown .= "### Метод `" . $method->getName() . "`\n\n";
                    if (!empty($summary)) {
                        $markdown .= "- **Краткое описание**: $summary\n";
                    }
                    if (!empty($description)) {
                        $markdown .= "- **Подробное описание**: $description\n";
                    }
                    $markdown .= $this->formatTags($docBlock->getTags());
                } catch (\Exception $e) {
                    $markdown .= "- **Описание**: Не удалось разобрать комментарий.\n";
                }
            }
        }

        return $markdown;
    }

    /**
     * Форматирует теги (аннотации) docblock.
     *
     * @param array $tags Массив тегов
     * @return string Отформатированные теги
     */
    private function formatTags(array $tags): string
    {
        $formattedTags = [];
        foreach ($tags as $tag) {
            $tagName = ucfirst($tag->getName());
            $tagContent = trim(str_replace('@' . $tag->getName(), '', $tag->render()));
            $formattedTags[] = "- **$tagName**: $tagContent";
        }

        if (!empty($formattedTags)) {
            return "\n" . implode("\n", $formattedTags) . "\n";
        }

        return "";
    }
}