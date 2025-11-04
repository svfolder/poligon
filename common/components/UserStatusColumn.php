<?php


namespace common\components;

use core\entities\User\User;
use core\helpers\UserHelper;
use yii\grid\DataColumn;

class UserStatusColumn extends DataColumn
{

    public $attribute = 'status';
    public $format = 'raw';

    public function __construct($config = [])
    {
        $config['filter'] = UserHelper::statusList();

        $config['value'] = function (User $model) {
            return UserHelper::statusLabel($model->status);
        };

        parent::__construct($config);
    }
}