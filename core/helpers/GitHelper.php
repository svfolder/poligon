<?php

namespace core\helpers;

use core\services\CodeCollectorService;

/**
 * Статический хелпер для работы с Git.
 * Все методы — статические. Экземпляры не создаются.
 */
class GitHelper
{
    /**
     * Получает список файлов из последнего коммита (HEAD).
     *
     * @param string $repoPath Путь к репозиторию.
     * @return array Список изменённых файлов.
     * @throws \Exception Если путь не является Git-репозиторием или произошла ошибка в git.
     */
    public static function getChangedPhpFilesFromLastCommit(string $repoPath): array
    {
        return self::getChangedPhpFilesFromGitRevision($repoPath, 'HEAD');
    }

    /**
     * Получает список файлов из конкретной ревизии по хешу.
     *
     * @param string $repoPath Путь к репозиторию.
     * @param string $revision Хеш коммита.
     * @return array Список изменённых файлов.
     * @throws \Exception Если путь не является Git-репозиторием или произошла ошибка в git.
     */
    public static function getChangedPhpFilesFromGitRevision(string $repoPath, string $revision): array
    {
        if (!self::isGitRepo($repoPath)) {
            throw new \Exception("Путь не является Git-репозиторием: $repoPath");
        }

        $cmd = "cd /d \"" . $repoPath . "\" && git diff --name-only {$revision}~1 {$revision} 2>&1";
        exec($cmd, $output, $exitCode);

        if ($exitCode !== 0) {
            throw new \Exception(
                "Не удалось получить изменения для ревизии '$revision'. " .
                "Возможно, хеш указан неверно, коммит не существует или это не Git-репозиторий."
            );
        }

        return array_values(array_filter($output, static function ($file) {
            $ext = pathinfo($file, PATHINFO_EXTENSION);
            return in_array($ext, CodeCollectorService::ALLOWED_EXTENSIONS, true);
        }));
    }

    /**
     * Проверяет, является ли директория Git-репозиторием.
     *
     * @param string $path Путь к директории.
     * @return bool Является ли директория Git-репозиторием.
     */
    public static function isGitRepo(string $path): bool
    {
        $cmd = "cd /d \"" . $path . "\" && git rev-parse --is-inside-work-tree 2>nul";
        exec($cmd, $output, $exitCode);
        return $exitCode === 0;
    }
}