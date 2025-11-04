<?php

namespace console\controllers;

use yii\console\Controller;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\DumpException;

class YamlController extends Controller
{
    /**
     * Генерирует YAML-файл на основе данных проекта и примеров PHP-кода.
     */
    public function actionGenerate()
    {
        // Базовая структура данных для YAML
        $data = [
            'PHP' => [
                'version' => 7.3,
            ],
            'project_structure' => [
                'backend' => ['assets', 'config', 'controllers', 'messages', 'views', 'widgets'],
                'common' => ['auth', 'bootstrap', 'components', 'config', 'fixtures', 'mail', 'models', 'widgets'],
                'console' => ['config', 'controllers', 'models'],
                'core' => [
                    'access', 'actions', 'behaviors', 'delegate', 'dispatchers', 'dto', 'entities', 'events',
                    'forms', 'helpers', 'jobs', 'listeners', 'readModels', 'repositories', 'rules', 'services',
                    'traits', 'useCases', 'validators', 'widgets',
                ],
            ],
            'dependencies' => [
                'require' => [
                    'ext-gd' => '*',
                    'ext-http' => '*',
                    'ext-json' => '*',
                    'ext-xmlwriter' => '*',
                    'nette/php-generator' => '*',
                    'athari/yalinqo' => '*',
                    'battye/php-array-parser' => '*',
                    'dmstr/yii2-adminlte-asset' => '*',
                    'filsh/yii2-oauth2-server' => '^2.0',
                    'fishvision/yii2-migrate' => '*',
                    'guzzlehttp/guzzle' => '*',
                    'himiklab/yii2-sortable-grid-view-widget' => '*',
                    'ihor/nspl' => '*',
                    'insolita/yii2-adminlte-widgets' => '^3.2',
                    'karriere/json-decoder' => '*',
                    'kartik-v/yii2-export' => '@dev',
                    'kartik-v/yii2-field-range' => '*',
                    'kartik-v/yii2-icons' => '@dev',
                    'kartik-v/yii2-widget-datepicker' => '*',
                    'kartik-v/yii2-widget-depdrop' => '@dev',
                    'kartik-v/yii2-widget-select2' => '*',
                    'kmergen/yii2-language-switcher' => '@dev',
                    'la-haute-societe/yii2-save-relations-behavior' => '*',
                    'league/flysystem' => '^1.0',
                    'loveorigami/yii2-jsoneditor' => '*',
                    'loveorigami/yii2-modal-ajax' => '*',
                    'mihaildev/yii2-ckeditor' => '@dev',
                    'omgdef/yii2-multilingual-behavior' => '~2',
                    'paulzi/yii2-nested-sets' => '*',
                    'ruskid/yii2-csv-importer' => '*',
                    'sjaakp/yii2-illustrated-behavior' => '*',
                    'unclead/yii2-multiple-input' => '*',
                    'vlucas/phpdotenv' => '*',
                    'yii-dream-team/yii2-upload-behavior' => '*',
                    'yii2mod/yii2-editable' => '*',
                    'yiimaker/yii2-social-share' => '*',
                    'yiisoft/yii2' => '=2.0.41',
                    'yiisoft/yii2-authclient' => '~2.2.0',
                    'yiisoft/yii2-bootstrap' => '~2.0.0',
                    'yiisoft/yii2-imagine' => '*',
                    'yiisoft/yii2-jui' => '~2.0.0',
                    'yiisoft/yii2-queue' => '2.3.2',
                    'yiisoft/yii2-redis' => '2.0.16',
                    'yiisoft/yii2-swiftmailer' => '~2.0.0 || ~2.1.0',
                ],
                'require-dev' => [
                    'codeception/codeception' => '^4.0',
                    'codeception/module-asserts' => '^1.0',
                    'codeception/module-filesystem' => '^1.0',
                    'codeception/module-yii2' => '^1.0',
                    'codeception/verify' => '~0.5.0 || ~1.1.0',
                    'electrolinux/phpquery' => '*',
                    'insolita/yii2-migration-generator' => '*',
                    'sixlive/dotenv-editor' => '*',
                    'symfony/browser-kit' => '>=2.7 <=4.2.4',
                    'yiisoft/yii2-debug' => '~2.1.0',
                    'yiisoft/yii2-faker' => '~2.0.0',
                    'yiisoft/yii2-gii' => '~2.1.0',
                ],
            ],
            'path_aliases' => [
                '@common' => "dirname(__DIR__)",
                '@frontend' => "dirname(dirname(__DIR__)) . '/frontend'",
                '@backend' => "dirname(dirname(__DIR__)) . '/backend'",
                '@api' => "dirname(dirname(__DIR__)) . '/api'",
                '@console' => "dirname(dirname(__DIR__)) . '/console'",
                '@core' => "dirname(dirname(__DIR__)) . '/core'",
                '@upload' => "dirname(dirname(__DIR__)) . '/frontend/web/upload'",
                '@root' => "dirname(dirname(__DIR__))",
            ],
            'rules' => [
                'structure_rules' => [],
                'output_format' => [
                    [
                        'rule' => 'Мой стиль кодирования основан на стиле ElisDN.',
                        'priority' => 'high',
                    ],
                ],
                'content_requirements' => [
                    [
                        'rule' => 'Используй пакеты из Yii2, вместо нативного PHP кода.',
                        'priority' => 'critical',
                    ],
                    [
                        'rule' => 'Анализируй список зависимостей проекта dependencies (если можно что то использовать в коде, используй)!',
                        'priority' => 'critical',
                    ],
                    [
                        'rule' => 'Используй пакеты из dependencies, вместо нативного PHP кода, если не нашла решения из Yii2',
                        'priority' => 'critical',
                    ],
                    [
                        'rule' => 'При выборе пакетов следуй иерархии приоритета: Yii2 > зависимости проекта > нативный PHP.',
                        'priority' => 'critical',
                    ],
                    [
                        'rule' => 'Выдавай весь код!',
                        'priority' => 'critical',
                    ],
                ],
                'additional_instructions' => [
                    [
                        'rule' => 'Использовать этот файл как справочник для понимания структуры проекта!',
                        'priority' => 'critical',
                    ],
                ],
            ],
            'php_examples' => [
                'correct_formatting' => [],
                'incorrect_formatting' => [],
            ],
            'service_layer_examples' => [
                'service_layer' => [],
                'transactions' => [],
                'helpers' => [],
            ],
        ];

        // Добавление примеров PHP-файлов
        $this->addPhpExamples($data);

        try {
            // Преобразуем массив данных в YAML с отступами
            $yamlContent = Yaml::dump($data, 10, 4, Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK);

            // Минифицируем только структуру YAML, оставляя форматирование блоков с примерами кода
            $minifiedContent = $this->minifyYamlStructure($yamlContent);

            // Путь к файлу
            $filePath = \Yii::getAlias('@console') . '/runtime/project_structure.yaml';

            // Сохраняем минифицированный YAML в файл
            file_put_contents($filePath, $minifiedContent);

            $this->stdout("YAML-файл успешно создан и минифицирован: {$filePath}\n", \yii\helpers\Console::FG_GREEN);
        } catch (DumpException $e) {
            $this->stderr("Ошибка при создании YAML-файла: " . $e->getMessage() . "\n", \yii\helpers\Console::FG_RED);
        }
    }

    private function addPhpExamples(&$data)
    {
        $exampleFiles = [
            'correct_formatting' => [
                '@core/services/RoleManager.php',
                '@core/helpers/ArrayHelper.php',
            ],
            'incorrect_formatting' => [
                '@core/examples/IncorrectExample1.php',
                '@core/examples/IncorrectExample2.php',
            ],
            'service_layer' => [
                '@core/services/UserService.php',
            ],
            'transactions' => [
                '@core/examples/TransactionExample.php',
            ],
            'helpers' => [
                '@core/helpers/ArrayHelper.php',
            ],
        ];

        foreach ($exampleFiles as $category => $files) {
            foreach ($files as $file) {
                try {
                    $filePath = \Yii::getAlias($file);
                    if (file_exists($filePath)) {
                        $fileName = basename($file);
                        $data['php_examples'][$category][$fileName] = $this->formatAsYamlBlock(file_get_contents($filePath));
                    } else {
                        \Yii::warning("Файл примера не найден: {$filePath}");
                    }
                } catch (\Exception $e) {
                    \Yii::error("Ошибка при чтении файла примера {$file}: " . $e->getMessage());
                }
            }
        }
    }

    private function formatAsYamlBlock($content)
    {
        // Минимальное экранирование
        $escapedContent = str_replace(
            ['|', '>'],
            ['\\|', '\\>'],
            $content
        );

        // Разбиваем на строки
        $lines = explode("\n", $escapedContent);

        // Формируем блок с корректными отступами
        $indentedLines = array_map(function($line) {
            return '    ' . $line; // 4 пробела для YAML
        }, $lines);

        // Используем | для сохранения форматирования
        return '|-' . "\n" . implode("\n", $indentedLines);
    }

    /**
     * Минифицирует только структуру YAML, оставляя форматирование блоков с примерами кода.
     *
     * @param string $yamlContent Содержимое YAML-файла
     * @return string Минифицированное содержимое YAML-файла
     */
    private function minifyYamlStructure($yamlContent)
    {
        // Разделяем содержимое на блоки с примерами кода и остальную структуру YAML
        $blocks = [];
        $currentBlock = '';
        $inBlock = false;

        $lines = explode("\n", $yamlContent);
        foreach ($lines as $line) {
            if (preg_match('/^\s*-\s*\|-\s*$/', $line)) {
                if ($currentBlock !== '') {
                    $blocks[] = $currentBlock;
                    $currentBlock = '';
                }
                $inBlock = true;
                $currentBlock .= $line . "\n";
            } elseif ($inBlock && preg_match('/^\s{4}/', $line)) {
                $currentBlock .= $line . "\n";
            } else {
                if ($currentBlock !== '') {
                    $blocks[] = $currentBlock;
                    $currentBlock = '';
                }
                $inBlock = false;
                $currentBlock .= $line . "\n";
            }
        }
        if ($currentBlock !== '') {
            $blocks[] = $currentBlock;
        }

        // Минифицируем только структуру YAML
        $minifiedContent = '';
        foreach ($blocks as $block) {
            if (preg_match('/^\s*-\s*\|-\s*$/', trim($block))) {
                // Это блок с примером кода, оставляем без изменений
                $minifiedContent .= $block;
            } else {
                // Минифицируем структуру YAML
                $minifiedBlock = preg_replace('/\s+:\s+/', ': ', $block);
                $minifiedBlock = preg_replace('/\s*,\s+/', ', ', $minifiedBlock);
                $minifiedBlock = preg_replace('/\s+-\s+/', '- ', $minifiedBlock);
                $minifiedBlock = preg_replace('/\n\s*\n+/', "\n", $minifiedBlock);
                $minifiedContent .= $minifiedBlock;
            }
        }

        return trim($minifiedContent);
    }
}