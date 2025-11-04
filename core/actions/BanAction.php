<?php

namespace core\actions;

use Yii;
use yii\base\Action;


class BanAction extends Action
{
    public function run($id, $is_banned)
    {
        try {

            $model = $this->controller->findModel($id);
            $model->is_banned = (int)$is_banned;
            $model->save();

        } catch (\DomainException $e) {
            Yii::$app->errorHandler->logException($e);
            Yii::$app->session->setFlash('error', $e->getMessage());
        }

        return $this->controller->redirect(['index']);
    }
}