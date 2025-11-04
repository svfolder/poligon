<?php

namespace common\components;

use yii\helpers\VarDumper;
use core\dto\DTOTrace;
use Yii;

class Dumper extends VarDumper
{
    /**
     * @param mixed $var
     * @param int $depth
     * @param bool $highlight
     */
    public static function dump($var, $depth = 10, $highlight = true)
    {
        parent::dump($var, $depth, $highlight);
        echo '<br/><br/>';
    }

    /**
     * @param mixed $var
     * @param int $depth
     * @param bool $highlight
     */
    public static function dd($var, $depth = 10, $highlight = true)
    {
        ob_clean();
        parent::dump($var, $depth, $highlight);
        exit('');
    }

    /**
     * @param int $limit
     * @param bool $highlight
     * @param bool $showArgs
     * @param int $argsDepth
     */
    public static function bt(
        $limit = 10,
        $highlight = true,
        $showArgs = true,
        $argsDepth = 3
    ) {
        ob_clean();
        $backtrace = array_slice(
            debug_backtrace(0, $limit + 1),
            1
        );

        $formattedBacktrace = [];
        foreach ($backtrace as $i => $trace) {
            $dto = new DTOTrace(
                $trace,
                $backtrace[$i + 1] ?? null,
                $showArgs,
                $argsDepth
            );

            $formattedBacktrace[] = $dto->toArray();
        }

        parent::dump($formattedBacktrace, $limit, $highlight);
        exit('');
    }

    /**
     * @param bool $highlight
     * @param bool $showArgs
     * @param int $argsDepth
     */
    public static function traceCalls(
        $highlight = true,
        $showArgs = true,
        $argsDepth = 3
    ) {
        $backtrace = debug_backtrace(0);

        // Определяем имя текущего метода
        $currentMethod = '';
        foreach ($backtrace as $trace) {
            if (isset($trace['function']) && $trace['function'] === __FUNCTION__) {
                continue; // Пропускаем сам вызов traceCalls
            }
            if (isset($trace['function'])) {
                $currentMethod = $trace['function'];
                break;
            }
        }

        // Фильтруем вызовы, связанные с текущим методом
        $filteredCalls = [];
        foreach ($backtrace as $i => $trace) {
            $callMethod = $trace['function'] ?? '';

            // Проверяем, что вызов происходит из текущего метода
            if ($callMethod === $currentMethod) {
                // Создаем объект DTO для текущего вызова
                $dto = new DTOTrace(
                    $trace,
                    $backtrace[$i + 1] ?? null,
                    $showArgs,
                    $argsDepth
                );

                $filteredCalls[] = $dto->toArray();
            }
        }

        // Выводим информацию о вызовах
        self::dump($filteredCalls, 10, $highlight);
    }

    /**
     * Логирует стек вызовов в файл.
     *
     * @param string $methodName Имя метода для логирования.
     * @param bool $showArgs Показывать ли аргументы.
     * @param int $argsDepth Уровень вложенности аргументов.
     */
    public static function logMethodCallsToFile(
        string $methodName,
        bool $showArgs = true,
        int $argsDepth = 3
    ) {
        $backtrace = debug_backtrace(0);

        // Фильтруем вызовы, связанные с указанным методом
        $filteredCalls = [];
        foreach ($backtrace as $i => $trace) {
            $callMethod = $trace['function'] ?? '';

            // Проверяем, что вызов происходит из указанного метода
            if ($callMethod === $methodName) {
                // Создаем объект DTO для текущего вызова
                $dto = new DTOTrace(
                    $trace,
                    $backtrace[$i + 1] ?? null,
                    $showArgs,
                    $argsDepth
                );

                $filteredCalls[] = $dto->toArray();
            }
        }

        // Записываем данные в файл
        self::writeToLogFile($filteredCalls, $methodName);
    }

    /**
     * Записывает данные в лог-файл.
     *
     * @param array $stackData Данные стека вызовов.
     * @param string $methodName Имя метода.
     */
    private static function writeToLogFile(array $stackData, string $methodName)
    {
        // Путь к файлу логов
        $logDir = Yii::getAlias('@root') . '/doc/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0777, true); // Создаем директорию, если она не существует
        }

        $logFile = $logDir . '/' . $methodName . '.log';

        // Преобразуем данные в строку для записи
        $logContent = date('Y-m-d H:i:s') . " - Trace Calls for method '$methodName':\n";
        $logContent .= print_r($stackData, true) . "\n\n";

        // Добавляем данные в файл
        file_put_contents($logFile, $logContent, FILE_APPEND);
    }
}