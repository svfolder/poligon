<?php


namespace common\components;

use yii\grid\ActionColumn;
use yii\helpers\Url;

class UserActionColumn extends ActionColumn
{
    public function __construct($config = [])
    {
        $config['urlCreator'] = function ($action, $model, $key, $index) {
            if ($action == 'update') {
                return Url::toRoute(['user/update-minimal', 'id' => $model->id]);
            } else {
                return Url::toRoute([$action, 'id' => $model->id]);
            }
        };

        parent::__construct($config);
    }

}