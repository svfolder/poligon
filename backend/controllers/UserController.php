<?php

namespace backend\controllers;

use Yii;
use core\entities\User\User;
use core\entities\User\UserSearch;
use core\forms\User\UserForm;
use core\services\user\UserService;
use core\traits\UserControllerTrait;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class UserController extends Controller
{
    use UserControllerTrait;

    /** @var UserService service */
    private $service;

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
                    ['allow' => true, 'actions' => ['create'], 'roles' => ['createAdmin']],
                    ['allow' => true, 'actions' => ['update'], 'roles' => ['createAdmin']],
                    ['allow' => true, 'actions' => ['delete'], 'roles' => ['createAdmin']],
                    ['allow' => true, 'actions' => ['banned'], 'roles' => ['doBanned']],
                    ['allow' => true, 'actions' => ['create-minimal'], 'roles' => ['createAdmin']],
                    ['allow' => true, 'actions' => ['update-minimal'], 'roles' => ['createAdmin']],
                ],
                'denyCallback' => function ($rule, $action){
                    Yii::$app->session->setFlash('error', Yii::t('app','You are denied access!'));
                    $this->redirect(Yii::$app->request->referrer ?: Yii::$app->homeUrl);
                },
            ],
        ];
    }

    /**
     * UserController constructor
     * @var $id
     * @var $module
     * @var UserService
     * @var array $config
     */
    public function __construct($id, $module, UserService $service, $config = [])
    {
        parent::__construct($id, $module, $config);
        $this->service = $service;
    }

    /**
     * Lists all User models.
     * @return mixed
     */
    public function actionIndex(): string
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider]);
    }

    /**
     * Displays a single User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id): string
    {
        return $this->render('view', ['model' => $this->findModel($id)]);
    }

    /**
     * Creates a new User model.
     * @return mixed
     */
    public function actionCreate()
    {
        $form = new UserForm();
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $user = $this->service->create($form);
                return $this->redirect(['index']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('create', ['model' => $form]);
    }

    /**
     * Updates an existing User model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $user = $this->findModel($id);

        $form = new UserForm($user);
        if ($form->load(Yii::$app->request->post()) && $form->validate()) {
            try {
                $this->service->edit($user->id, $form);
                return $this->redirect(['index']);
            } catch (\DomainException $e) {
                Yii::$app->errorHandler->logException($e);
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        return $this->render('update', ['model' => $form]);
    }

    /**
     * Deletes an existing User model.
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
     * Finds the User model based on its primary key value
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findModel($id): User
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
