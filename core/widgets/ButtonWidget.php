<?php


namespace core\widgets;


use yii\base\Model;
use yii\base\Widget;


class ButtonWidget extends Widget
{

    /**
     * @var Model
     */
    public $model;

    /**
     * @var string
     */
    public $method;

    /**
     * @var string
     */
    public $icon;

    /**
     * @var string
     */
    public $text;

    /**
     * @var string
     */
    public $button_class;

    public function init()
    {
        parent::init();
    }

    public function run(): string
    {
        return $this->render('button_widget', [
            'model' => $this->model,
            'method' => $this->method,
            'icon' => $this->icon,
            'class' => $this->button_class,
            'text' => $this->text,
        ]);
    }

}