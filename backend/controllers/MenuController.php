<?php

namespace backend\controllers;

use Yii;
use core\entities\Menu\Menu;
use core\entities\Menu\MenuSearch;
use core\forms\Menu\MenuForm;
use core\services\menu\MenuService;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class MenuController extends Controller
{
    /** @var MenuService service */
    public $service;

    public function actions(): array
    {
        return [
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
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    ['allow' => true, 'actions' => ['index'], 'roles' => ['manager']],
                    ['allow' => true, 'actions' => ['view'], 'roles' => ['manager']],
                    ['allow' => true, 'actions' => ['create'], 'roles' => ['manager']],
                    ['allow' => true, 'actions' => ['update'], 'roles' => ['stopDemo']],
                    ['allow' => true, 'actions' => ['delete'], 'roles' => ['stopDemo']],
                    ['allow' => true, 'actions' => ['active'], 'roles' => ['stopDemo']],
                ],
                'denyCallback' => function ($rule, $action){
                    Yii::$app->session->setFlash('error', Yii::t('app','You are denied access!'));
                    $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
                },
            ],
        ];
    }

    /**
     * MenuController constructor
     * @var $id
     * @var $module
     * @var MenuService
     * @var array $config
     */
    public function __construct($id, $module, MenuService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    /**
     * Lists all Menu models.
     * @return mixed
     */
    public function actionIndex(): string
    {
        $searchModel = new MenuSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }

    /**
     * Displays a single Menu model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id): string
    {
        return $this->render('view', ['model' => $this->findModel($id)]);
    }

    /**
     * Creates a new Menu model.
     * @return mixed
     */
    public function actionCreate()
    {
        $form = new MenuForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $menu = $this->service->create($form);
                return $this->redirect(['index']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('create', ['model' => $form]);
    }

    /**
     * Updates an existing Menu model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $menu = $this->findModel($id);

        $form = new MenuForm($menu);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($menu->id, $form);
                return $this->redirect(['index']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('update', ['model' => $form]);
    }

    /**
     * Deletes an existing Menu model.
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
     * Finds the Menu model based on its primary key value
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id): Menu
    {
        if (($model = Menu::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
