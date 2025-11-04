<?php

namespace core\dto;

use yii\helpers\VarDumper;
use Yii;

/**
 * DTOTrace представляет собой объект для хранения и обработки данных трассировки вызовов.
 */
class DTOTrace
{
    /**
     * @var string
     */
    public $call;

    /**
     * @var string
     */
    public $file;

    /**
     * @var string
     */
    public $line;

    /**
     * @var mixed
     */
    public $args;

    /**
     * @var string
     */
    public $caller;

    /**
     * @param array $trace
     * @param array|null $nextTrace
     * @param bool $showArgs
     * @param int $argsDepth
     */
    public function __construct(
        array $trace,
        ?array $nextTrace = null,
        bool $showArgs = true,
        int $argsDepth = 3
    ) {
        $rootPath = Yii::getAlias('@root') . DIRECTORY_SEPARATOR;

        $this->call = isset($trace['class'])
            ? $trace['class'] . $trace['type'] . $trace['function']
            : $trace['function'];

        $this->file = $trace['file'] ?? 'unknown file';
        $this->file = str_replace($rootPath, '', $this->file);
        $this->line = $trace['line'] ?? 'unknown line';

        $this->args = $showArgs && isset($trace['args'])
            ? self::limitDepth($trace['args'], $argsDepth)
            : 'arguments disabled';

        $this->caller = isset($nextTrace['class'])
            ? $nextTrace['class'] . $nextTrace['type'] . $nextTrace['function']
            : 'unknown caller';
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'call' => $this->call,
            'file' => $this->file,
            'line' => $this->line,
            'args' => $this->args,
            'caller' => $this->caller,
        ];
    }

    /**
     * @param mixed $data
     * @param int $depth
     * @return mixed
     */
    private static function limitDepth($data, $depth = 3)
    {
        if ($depth < 1) {
            return '...';
        }

        if (is_array($data)) {
            return array_map(function ($item) use ($depth) {
                return self::limitDepth($item, $depth - 1);
            }, $data);
        }

        if (is_object($data)) {
            return [
                'class' => get_class($data),
                'properties' => self::limitDepth((array)$data, $depth - 1),
            ];
        }

        return $data;
    }
}