<?php

namespace console\controllers;

use core\dto\DTOCollectConfig;
use yii\console\Controller;
use core\services\CodeCollectorService;
use yii\helpers\Console;
use yii\base\Module;

/**
 * Консольный контроллер для сбора кода проекта.
 */
class CollectCodeController extends Controller
{
    /** @var CodeCollectorService */
    private $service;

    /**
     * CollectCodeController constructor.
     *
     * @param string $id
     * @param Module $module
     * @param CodeCollectorService $service
     * @param array $config
     */
    public function __construct($id, $module, CodeCollectorService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    /**
     * Собирает код по указанному конфигу.
     *
     * @param string $configName Имя конфига (без .yaml).
     * @return void
     * @throws \ReflectionException
     */
    public function actionIndex(string $configName = 'config'): void
    {
        $configPath = \Yii::getAlias('@console') . "/config/collect/{$configName}.yaml";

        if (file_exists($configPath)) {
            $this->service->collect($configPath);
        } else {
            echo "Конфиг {$configName}.yaml не найден!\n";
        }
    }

    /**
     * Создаёт пример конфигурации.
     *
     * @param string $configName Имя конфига.
     * @return void
     */
    public function actionCreate(string $configName = 'default'): void
    {
        $dto = new DTOCollectConfig();

        $dto->include->dirs = ['@core/entities', '@core/services'];
        $dto->include->files = ['@core/helpers/ArrayHelper.php'];
        $dto->exclude->dirs = ['@core/tests'];
        $dto->exclude->files = ['@core/services/DebugService.php'];

        $dto->code_style['recommended']->files = ['@core/examples/recommended/ExampleService.php'];
        $dto->code_style['bad']->files = ['@core/examples/bad/ExampleService.php'];

        if ($this->service->saveConfig($dto, $configName)) {
            echo "Конфиг {$configName}.yaml создан\n";
        } else {
            echo "Ошибка при создании конфига\n";
        }
    }

    /**
     * Собирает код из указанного коммита Git.
     * Если ревизия не передана — используется HEAD.
     *
     * Примеры:
     *   yii collect-code/git
     *   yii collect-code/git abc1234
     *   yii collect-code/git feature/new-auth
     *
     * @param string|null $revision Хеш коммита, тег или ветка.
     * @return void
     * @throws \ReflectionException
     */
    public function actionGit(?string $revision = null): void
    {
        $this->service->collectFromGitCommit($revision);
    }
}