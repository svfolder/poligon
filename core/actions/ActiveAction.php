<?php

namespace core\actions;

use Yii;
use yii\base\Action;
use yii\web\Response;


class ActiveAction extends Action
{

    protected $service;
    protected $repository;

    public function run($id, $status): Response
    {
        try {

            if (
                isset($this->controller->service) &&
                method_exists($this->controller->service, 'getRepository')
            ){

                $this->service = $this->controller->service;
                $this->repository = $this->service->getRepository();

                $model = $this->repository->get($id);
                $model->status = (int)$status;

                $this->repository->save($model);

            }else{

                $model = $this->controller->findModel($id);
                $model->status = (int)$status;
                $model->save();

            }

        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->controller->redirect(['index']);
    }
}