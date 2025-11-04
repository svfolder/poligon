<?php


namespace common\components;


use core\entities\User\User;
use yii\grid\DataColumn;
use yii\helpers\Html;

class UserUsernameColumn extends DataColumn
{
    public $attribute = 'username';
    public $format = 'raw';

    public function __construct($config = [])
    {
        $config['value'] = function (User $model) {
            return Html::a(Html::encode($model->username), ['view', 'id' => $model->id]);
        };

        parent::__construct($config);
    }

}