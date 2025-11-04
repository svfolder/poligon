<?php


namespace common\components;

use core\entities\User\User;
use Yii;
use yii\grid\DataColumn;
use yii\helpers\Html;


class StatusColumn extends DataColumn
{

    const STATUS_DISABLE = 0;
    const STATUS_ENABLE = 1;

    public $headerOptions = [
        'style' => 'width: 80px;'
    ];

    public $contentOptions = [
        'class' => 'text-center'
    ];

    public $attribute = 'status';

    public $action = 'active';

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {

        $property = $this->attribute;

        if($model->$property == 1){

            return Html::a('<span class="glyphicon glyphicon-ok-circle"></span>', [
                $this->action, 'id' => $model->id, $this->attribute => 0], [
                    'title' => Yii::t('app', 'Unactivate this'),
                    'data-pjax' => '0',
                ]
            );

        }else{

            return Html::a('<span class="glyphicon glyphicon-lock"></span>', [
                $this->action, 'id' => $model->id, $this->attribute => 1], [
                    'title' => Yii::t('app', 'Activate this'),
                    'data-pjax' => '0',
                ]
            );

        }

    }

    public static function statusList(): array
    {
        return [
            self::STATUS_DISABLE => Yii::t('app', 'No'),
            self::STATUS_ENABLE => Yii::t('app', 'Yes'),
        ];
    }

    protected function renderFilterCellContent()
    {
        $this->filter = self::statusList();
        return parent::renderFilterCellContent();
    }


}