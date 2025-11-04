<?php

namespace backend\controllers;

use Yii;
use core\entities\Menu\MenuItem;
use core\entities\Menu\MenuItemSearch;
use core\forms\Menu\MenuItemForm;
use core\services\menu\MenuItemService;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class MenuItemController extends Controller
{
    /** @var MenuItemService service */
    public $service;

    public function actions(): array
    {
        return [
            'sort' => [
                'class' => \himiklab\sortablegrid\SortableGridAction::class,
                'modelName' => \core\entities\Menu\MenuItem::class,
            ],
            'active' => [
                'class' => \core\actions\ActiveAction::class,
            ],
        ];
    }

    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => ['delete' => ['POST']],
            ],
        ];
    }

    /**
     * MenuItemController constructor
     * @var $id
     * @var $module
     * @var MenuItemService
     * @var array $config
     */
    public function __construct($id, $module, MenuItemService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    /**
     * Lists all MenuItem models.
     * @return mixed
     */
    public function actionIndex(): string
    {
        $searchModel = new MenuItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }

    /**
     * Displays a single MenuItem model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id): string
    {
        return $this->render('view', ['model' => $this->findModel($id)]);
    }

    /**
     * Creates a new MenuItem model.
     * @return mixed
     */
    public function actionCreate()
    {
        $form = new MenuItemForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $menuItem = $this->service->create($form);
                return $this->redirect(['index']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('create', ['model' => $form]);
    }

    /**
     * Updates an existing MenuItem model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $menuItem = $this->findModel($id);

        $form = new MenuItemForm($menuItem);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($menuItem->id, $form);
                return $this->redirect(['index']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('update', ['model' => $form]);
    }

    /**
     * Deletes an existing MenuItem model.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id): Response
    {
        try {
            $this->service->remove($id);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the MenuItem model based on its primary key value
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id): MenuItem
    {
        if (($model = MenuItem::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
