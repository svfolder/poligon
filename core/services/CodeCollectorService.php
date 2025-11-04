<?php

namespace core\services;

use core\dto\DTOCollectConfig;
use core\helpers\CodeHelper;
use core\helpers\GitHelper;
use core\interfaces\CollectConfigInterface;
use yii\helpers\FileHelper;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Symfony\Component\Yaml\Yaml;

/**
 * Сервис для сбора кода проекта по конфигурации.
 */
class CodeCollectorService
{
    /** @var string[] Список разрешённых расширений файлов для сборки. Единая точка изменения. */
    public const ALLOWED_EXTENSIONS = [
        'php', 'js', 'ts', 'css', 'scss', 'less', 'html', 'phtml', 'twig', 'blade.php', 'json'
    ];

    /**
     * Собирает структуру кода на основе конфигурационного файла.
     *
     * @param string $inputConfigPath Путь к YAML-конфигурации.
     * @return void
     * @throws \ReflectionException
     */
    public function collect(string $inputConfigPath = '@console/config/collect-code-config.yaml'): void
    {
        $configPath = \Yii::getAlias($inputConfigPath);
        $yamlContent = file_get_contents($configPath);
        $configData = Yaml::parse($yamlContent, Yaml::PARSE_CONSTANT);

        $config = new DTOCollectConfig();
        $config->include->dirs = $configData['include']['dirs'] ?? [];
        $config->include->files = $configData['include']['files'] ?? [];
        $config->exclude->dirs = $configData['exclude']['dirs'] ?? [];
        $config->exclude->files = $configData['exclude']['files'] ?? [];

        $config->code_style['recommended']->files = $configData['code_style']['recommended'] ?? [];
        $config->code_style['bad']->files = $configData['code_style']['bad'] ?? [];
        $config->code_style['forbidden']->files = $configData['code_style']['forbidden'] ?? [];

        $basePath = \Yii::getAlias('@root');
        $structure = [];

        foreach ($config->getIncludeFiles() as $filePath) {
            $realPath = \Yii::getAlias($filePath);
            if (in_array($filePath, $config->getExcludeFiles())) {
                continue;
            }

            if (is_file($realPath) && $this->isCodeOrViewFile($realPath)) {
                $this->processFile($realPath, $basePath, $structure);
            }
        }

        foreach ($config->getIncludeDirs() as $dir) {
            $dirPath = \Yii::getAlias($dir);
            if (in_array($dir, $config->getExcludeDirs())) {
                continue;
            }

            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($dirPath),
                RecursiveIteratorIterator::SELF_FIRST
            );

            foreach ($iterator as $file) {
                if (!$file->isFile()) {
                    continue;
                }
                $filePath = $file->getPathname();
                if (in_array($filePath, $config->getExcludeFiles())) {
                    continue;
                }

                if ($this->isCodeOrViewFile($filePath)) {
                    $this->processFile($filePath, $basePath, $structure);
                }
            }
        }

        $codeStyleExamples = [
            'code_style_examples' => [
                'recommended' => $this->loadCodeStyleExamples($config->getCodeStyleRecommended()),
                'bad' => $this->loadCodeStyleExamples($config->getCodeStyleBad()),
                'forbidden' => $this->loadCodeStyleExamples($config->getCodeStyleForbidden()),
            ],
        ];

        CodeHelper::saveToYaml(
            $codeStyleExamples,
            $basePath,
            \Yii::getAlias('@console/runtime/codestyle.yaml.txt')
        );

        $data = [
            'project_root' => basename($basePath),
            'structure' => $structure,
        ];

        CodeHelper::saveToYaml(
            $data,
            $basePath,
            \Yii::getAlias('@console/runtime/project_code.yaml.txt')
        );
    }

    /**
     * Обрабатывает один файл: извлекает информацию о классе или просто сохраняет содержимое.
     *
     * @param string $filePath Полный путь к файлу.
     * @param string $basePath Базовый путь проекта.
     * @param array &$structure Ссылка на структуру проекта.
     * @return void
     * @throws \ReflectionException
     */
    private function processFile(string $filePath, string $basePath, array &$structure): void
    {
        $relativePath = $this->getRelativePath($filePath, $basePath);
        $parts = array_filter(explode('/', dirname($relativePath)));
        $filename = basename($filePath);
        $content = file_get_contents($filePath);
        $ext = pathinfo($filename, PATHINFO_EXTENSION);

        $fileData = [
            'type' => $ext,
            'filename' => $filename,
            'path' => $relativePath,
        ];

        if ($ext === 'php') {
            $namespace = CodeHelper::extractNamespace($content);
            $className = CodeHelper::extractClassName($content);
            if ($className && $namespace) {
                $fullClassName = $namespace . '\\' . $className;
                $classInfo = CodeHelper::getClassInfo($fullClassName, $content);
                if ($classInfo) {
                    $fileData['classes'][$className] = $classInfo;
                    CodeHelper::addToStructure($structure, $parts, $filename, $fileData);
                    return;
                }
            }
        }

        $fileData['content'] = $content;
        CodeHelper::addToStructure($structure, $parts, $filename, $fileData);
    }

    /**
     * Загружает содержимое файлов-примеров стиля кода.
     *
     * @param array $files Список путей к файлам.
     * @return array Ассоциативный массив: путь => содержимое.
     */
    private function loadCodeStyleExamples(array $files): array
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

    /**
     * Сохраняет конфигурацию в YAML-файл.
     *
     * @param CollectConfigInterface $config Объект конфигурации.
     * @param string $configName Имя файла (без расширения).
     * @return bool Успешно ли сохранено.
     */
    public function saveConfig(CollectConfigInterface $config, string $configName): bool
    {
        $configDir = \Yii::getAlias('@console/config/collect');
        if (!is_dir($configDir)) {
            if (!mkdir($configDir, 0755, true)) {
                return false;
            }
        }

        $yamlContent = Yaml::dump(
            $config->export(),
            10,
            4,
            Yaml::DUMP_MULTI_LINE_LITERAL_BLOCK
        );

        $configPath = $configDir . '/' . $configName . '.yaml';
        return file_put_contents($configPath, $yamlContent) !== false;
    }

    /**
     * Собирает код из указанного коммита Git.
     * Если ревизия не передана — используется HEAD.
     *
     * @param string|null $revision Хеш коммита, тег или ветка.
     * @return void
     * @throws \ReflectionException
     */
    public function collectFromGitCommit(?string $revision = null): void
    {
        $basePath = \Yii::getAlias('@root');
        $structure = [];

        try {
            $files = $revision === null
                ? GitHelper::getChangedPhpFilesFromLastCommit($basePath)
                : GitHelper::getChangedPhpFilesFromGitRevision($basePath, $revision);
        } catch (\Exception $e) {
            echo "Ошибка: " . $e->getMessage() . "\n";
            return;
        }

        foreach ($files as $filePath) {
            $fullPath = $basePath . '/' . $filePath;
            if (file_exists($fullPath) && $this->isCodeOrViewFile($fullPath)) {
                $this->processFile($fullPath, $basePath, $structure);
            }
        }

        $outputFilename = $revision
            ? "project_code_from_git_{$revision}.yaml.txt"
            : "project_code_from_git.yaml.txt";

        $data = [
            'project_root' => basename($basePath),
            'git_revision' => $revision ?: 'HEAD',
            'structure' => $structure,
            'code_style_examples' => [],
        ];

        CodeHelper::saveToYaml($data, $basePath, \Yii::getAlias("@console/runtime/{$outputFilename}"));

        $rev = $revision ?: 'HEAD';
        echo "Файлы из Git (ревизия: $rev) успешно собраны в {$outputFilename}\n";
    }

    /**
     * Проверяет, является ли файл файлом кода или вёрстки.
     *
     * @param string $filePath Путь к файлу.
     * @return bool
     */
    private function isCodeOrViewFile(string $filePath): bool
    {
        $ext = pathinfo($filePath, PATHINFO_EXTENSION);
        return in_array($ext, self::ALLOWED_EXTENSIONS, true);
    }

    /**
     * Вычисляет относительный путь от базового каталога.
     *
     * @param string $filePath Полный путь к файлу.
     * @param string $basePath Базовый путь проекта.
     * @return string Относительный путь (с нормализацией слешей).
     */
    private function getRelativePath(string $filePath, string $basePath): string
    {
        $normalizedPath = FileHelper::normalizePath($filePath);
        $normalizedBase = FileHelper::normalizePath($basePath);
        return ltrim(str_replace($normalizedBase, '', $normalizedPath), '/');
    }
}