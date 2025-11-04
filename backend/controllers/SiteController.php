<?php
namespace backend\controllers;

use core\repositories\NotFoundException;
use core\rules\BannedRule;
use core\services\CodeCollectorService;
use Yii;
use yii\web\Controller;
use yii\web\Response;

/**
 * Site controller
 */
class SiteController extends Controller
{

    /**
     * @var CodeCollectorService
     */
    private $collectorService;

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function __construct($id, $module, CodeCollectorService $collectorService, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->collectorService = $collectorService;
    }

    public function actionIndex(): string
    {
        return $this->render('index');
    }

    /**
     * @throws \Exception
     */
    public function actionRule()
    {

        $auth = Yii::$app->authManager;

        // Создаем правило
        $rule = new BannedRule();
        $auth->add($rule);

        //  Создаем разрешение, и привязываем к нему правило
        $doBanned = $auth->createPermission('doBanned');
        $doBanned->description = 'Бан только узеров';
        $doBanned->ruleName = $rule->name;
        $auth->add($doBanned);

        //  Достаем из базы менеджера
        $manager = $auth->getRole('manager');

        // Делаем наследование менеджером, разрешения
        $auth->addChild($manager, $doBanned);

    }

    /**
     * @return string|Response
     * @throws \Exception
     */
    public function actionCollect()
    {
        try {
            $configPath = \Yii::getAlias('@console') . "/config/collect/config.yaml";

            $this->collectorService->collect($configPath);
            return $this->redirect(['index']);
        } catch (NotFoundException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->render('collect');

    }

}
