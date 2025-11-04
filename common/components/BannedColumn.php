<?php


namespace common\components;

use core\helpers\UserHelper;
use Yii;
use yii\grid\DataColumn;
use yii\helpers\Html;


class BannedColumn extends DataColumn
{

    public $headerOptions = [
        'style' => 'width: 110px;'
    ];

    public $contentOptions = [
        'class' => 'text-center'
    ];

    public $attribute = 'is_banned';
    public $format = 'raw';
    public $action;

    public function __construct($config = [])
    {
        $config['filter'] = UserHelper::bannedList();

        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {

        if($model->is_banned == 0){

            return Html::a('<span class="glyphicon glyphicon-ok-circle"></span>', [
                'banned', 'id' => $model->id, $this->attribute => 1], [
                    'title' => Yii::t('app', 'Unbanned this'),
                    'data-pjax' => '0',
                    'data-method' => 'post'
                ]
            );

        }else{

            return Html::a('<span class="glyphicon glyphicon-lock"></span>', [
                'banned', 'id' => $model->id, $this->attribute => 0], [
                    'title' => Yii::t('app', 'Banned this'),
                    'data-pjax' => '0',
                    'data-method' => 'post'
                ]
            );

        }

    }

}