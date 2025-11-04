<?php


namespace common\widgets\vue;


use yii\base\Widget;
use yii\helpers\Html;

class Vue extends Widget
{
    /**
     * @var string
     */
    public $component;

    /**
     * @var array
     */
    public $props = [];

    public function init()
    {
        parent::init();
        VueAssets::register($this->view);
    }

    public function run()
    {
        parent::run();
        echo Html::tag(
            'div',
            Html::tag($this->component, '', $this->props),
            [
                'id' => $this->getVueID()
            ]);

//        $this->view->registerJs("
//            new Vue({
//                el: '#" . $this->getVueID() . "',
//                data: {
//                    message: \"Hello Vue.js2\"
//                }
//            })
//        ");

        $this->view->registerJsFile('/admin/js/vue.js', ['position' => \yii\web\View::POS_END]);
    }

    public function getVueID(): string
    {
        return 'vue' . $this->getId();
    }
}