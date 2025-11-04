<?php

namespace backend\controllers\auth;

use Yii;
use core\entities\Auth\AuthItem;
use core\entities\Auth\AuthItemSearch;
use core\forms\auth\AuthItemForm;
use core\services\auth\AuthItemService;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class AuthItemController extends Controller
{
    /** @var AuthItemService service */
    private $service;

    public function actions(): array
    {
        return [
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
     * AuthItemController constructor
     * @var $id
     * @var $module
     * @var AuthItemService
     * @var array $config
     */
    public function __construct($id, $module, AuthItemService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    /**
     * Lists all AuthItem models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AuthItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }

    /**
     * Displays a single AuthItem model.
     * @param string $name
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($name)
    {
        return $this->render('view', ['model' => $this->findModel($name)]);
    }

    /**
     * Creates a new AuthItem model.
     * @return mixed
     */
    public function actionCreate()
    {
        $form = new AuthItemForm();
        if($form->load(Yii::$app->request->post()) && $form->validate()){
            try {
                $authItem = $this->service->create($form);
                return $this->redirect(['index']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('create', ['model' => $form]);
    }

    /**
     * Updates an existing AuthItem model.
     * @param string $name
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($name)
    {
        $authItem = $this->findModel($name);

        $form = new AuthItemForm($authItem);
        if($form->load(Yii::$app->request->post()) && $form->validate()){
            try {
                $this->service->edit($authItem->name, $form);
                return $this->redirect(['index']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('update', ['model' => $form]);
    }

    /**
     * Deletes an existing AuthItem model.
     * @param string $name
     * @return mixed
     */
    public function actionDelete($name)
    {
        try {
            $this->service->remove($name);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the AuthItem model based on its primary key value
     * @param string $name
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($name): AuthItem
    {
        if (($model = AuthItem::findOne($name)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
