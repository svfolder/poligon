<?php


namespace core\widgets;

use yii\base\Model;
use yii\base\Widget;
use yii\helpers\Html;

class AutoFillWidget extends Widget
{
    private $prefix = 'fill-';

    /**
     * @var Model
     */
    public $model;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string[]
     */
    public $fields = [];

    /**
     * @var string[]
     */
    protected $ids;

    public function init()
    {
        parent::init();

        foreach ($this->fields as $field){
            $this->ids[] = Html::getInputId($this->model, $field);
        }

        $this->name = "{$this->prefix}{$this->name}";
    }

    public function run(): string
    {
        return $this->render('auto_fill', ['name' => $this->name, 'ids' => $this->ids]);
    }

}