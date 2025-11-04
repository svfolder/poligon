<?php

namespace backend\controllers\auth;

use Yii;
use core\entities\Auth\AuthItemChild;
use core\entities\Auth\AuthItemChildSearch;
use core\forms\auth\AuthItemChildForm;
use core\services\auth\AuthItemChildService;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class AuthItemChildController extends Controller
{
    /** @var AuthItemChildService service */
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
     * AuthItemChildController constructor
     * @var $id
     * @var $module
     * @var AuthItemChildService
     * @var array $config
     */
    public function __construct($id, $module, AuthItemChildService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    /**
     * Lists all AuthItemChild models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AuthItemChildSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }

    /**
     * Displays a single AuthItemChild model.
     * @param string $parent
     * @param string $child
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($parent, $child)
    {
        return $this->render('view', ['model' => $this->findModel($parent, $child)]);
    }

    /**
     * Creates a new AuthItemChild model.
     * @return mixed
     */
    public function actionCreate()
    {
        $form = new AuthItemChildForm();
        if($form->load(Yii::$app->request->post()) && $form->validate()){
            try {
                $authItemChild = $this->service->create($form);
                return $this->redirect(['index']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('create', ['model' => $form]);
    }

    /**
     * Updates an existing AuthItemChild model.
     * @param string $parent
     * @param string $child
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($parent, $child)
    {
        $authItemChild = $this->findModel($parent, $child);

        $form = new AuthItemChildForm($authItemChild);
        if($form->load(Yii::$app->request->post()) && $form->validate()){
            try {
                $this->service->edit($authItemChild->parent, $authItemChild->child, $form);
                return $this->redirect(['index']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('update', ['model' => $form]);
    }

    /**
     * Deletes an existing AuthItemChild model.
     * @param string $parent
     * @param string $child
     * @return mixed
     */
    public function actionDelete($parent, $child)
    {
        try {
            $this->service->remove($parent, $child);
        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }
        return $this->redirect(['index']);
    }

    /**
     * Finds the AuthItemChild model based on its primary key value
     * @param string $parent
     * @param string $child
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($parent, $child): AuthItemChild
    {
        if (($model = AuthItemChild::findOne(['parent' => $parent, 'child' => $child])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
