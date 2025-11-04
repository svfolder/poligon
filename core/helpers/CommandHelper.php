<?php
namespace core\helpers;

use yii\helpers\FileHelper;
use yii\helpers\Inflector;
use ReflectionClass;
use yii\console\Controller as ConsoleController;

class CommandHelper
{
    /**
     * @param string $basePath
     * @return array
     * @throws \ReflectionException
     * @throws \Exception
     */
    public static function getCommands(string $basePath): array
    {
        if (!is_dir($basePath)) {
            throw new \Exception("Директория контроллеров не найдена: {$basePath}");
        }

        $commands = [];
        foreach (FileHelper::findFiles($basePath, ['only' => ['*.php']]) as $file) {
            $className = 'console\\controllers\\' . str_replace('.php', '', basename($file));

            if (self::isIgnoredCommand($className)) {
                continue;
            }

            if (is_subclass_of($className, ConsoleController::class)) {
                $reflection = new ReflectionClass($className);
                $commands[] = [
                    'name' => self::getCommandName($reflection),
                    'actions' => self::getActions($reflection),
                ];
            }
        }

        return $commands;
    }

    /**
     * @param string $className
     * @return bool
     */
    private static function isIgnoredCommand(string $className): bool
    {
        $ignoredCommands = [
            'console\\controllers\\MenuController',
        ];

        return in_array($className, $ignoredCommands, true);
    }

    /**
     * @param ReflectionClass $reflection
     * @return string
     */
    private static function getCommandName(ReflectionClass $reflection): string
    {
        $shortName = $reflection->getShortName();
        $nameWithoutController = Inflector::camel2id($shortName, '_');
        $nameWithoutController = str_replace('_controller', '', $nameWithoutController);
        return lcfirst(Inflector::id2Camel($nameWithoutController, '_'));
    }

    /**
     * @param ReflectionClass $reflection
     * @return array
     */
    private static function getActions(ReflectionClass $reflection): array
    {
        $actions = [];
        foreach ($reflection->getMethods() as $method) {
            if (
                strpos($method->getName(), 'action') === 0 &&
                $method->getDeclaringClass()->getName() === $reflection->getName() &&
                $method->isPublic()
            ) {
                $actionName = lcfirst(str_replace('action', '', $method->getName()));

                if (strlen($actionName) > 1) {
                    $actions[] = $actionName;
                }
            }
        }
        return $actions;
    }
}