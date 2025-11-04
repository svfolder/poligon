<?php

namespace core\actions;

use Yii;
use yii\base\Action;


class OnlineAction extends Action
{
    public function run($id, $is_online)
    {
        try {

            $model = $this->controller->findModel($id);
            $model->is_online = (int)$is_online;
            $model->save();

        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->controller->redirect(['index']);
    }
}