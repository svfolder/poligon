<?php


namespace core\widgets;

use yii\base\Model;
use yii\base\Widget;


class BatchWidget extends Widget
{

    /**
     * @var Model
     */
    public $model;

    public function init()
    {
        parent::init();
    }

    public function run()
    {
        return $this->render('batch', [
            'model' => $this->model,
        ]);
    }

}