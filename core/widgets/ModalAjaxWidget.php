<?php

namespace core\widgets;

use Yii;
use lo\widgets\modal\ModalAjax;
use yii\helpers\Inflector;
use yii\web\View;
use function nspl\a\last;


class ModalAjaxWidget extends ModalAjax
{

    /**
     * @var string
     */
    public $target;

    /**
     * @var string
     */
    public $parentSelector;

    /**
     * @var string
     */
    public $dependSelector;

    /**
     * @var string
     */
    public $type = 'select';

    /**
     * @var string
     */
    public $formID;

    public function init()
    {
        $this->toggleButton = [];
        parent::init();
        $this->initOptions();
    }

    public function run()
    {
        parent::run();

        $script = <<<SCRIPT

jQuery(function () {

    $('{$this->dependSelector}').change(function () {
        disableButton(this);
    });
    
    disableButton('{$this->dependSelector}');
    
});

function disableButton(element){
    if($(element).val() == 0){
        $('.button-{$this->target}').attr('disabled', 'disabled');
    }else{
        $('.button-{$this->target}').removeAttr('disabled');
    }
}

SCRIPT;

        $this->getView()->registerJs($script);
    }

    protected function initOptions()
    {
        parent::initOptions();

        $camelize = Inflector::camelize($this->target);

        $this->toggleButton = array_merge([
            'label' => Yii::t('app',"Create {$camelize}"),
            'class' => 'btn btn-xs btn-success pull-right ' . 'button-' . $this->target,
        ], $this->toggleButton);

        $this->header = '<h4>' . Yii::t('app',"Create {$camelize}") . '</h4>';

        $this->formID = "{$this->target}form";

        if ($this->parentSelector){
            $this->type = 'select';
        }else{
            $this->type = 'dep';
        }

        if ($this->type == 'select'){

            $this->events = [
                ModalAjaxWidget::EVENT_MODAL_SUBMIT_COMPLETE => new \yii\web\JsExpression("
                        function(event, xhr, textStatus) {
                            if (textStatus == 'success') {
                                
                                $('{$this->parentSelector}').html('');
                                $('{$this->parentSelector}').append('<option value = \'\'>Выбрать из списка</option>');
                                $.each(xhr.responseJSON.data, function(key, val){
                                    $('{$this->parentSelector}').append('<option value = ' + key + '>' + val + '</option>');
                                })
                                
                                $(this).modal('toggle');
                                alert('Данные успешно добавлены.');
                            }
                        }
                    ")
            ];

        }elseif ($this->type == 'dep'){

            $field = last(explode("-", $this->dependSelector));

            $this->events = [
                ModalAjaxWidget::EVENT_MODAL_SUBMIT_COMPLETE => new \yii\web\JsExpression("
                        function(event, xhr, textStatus) {
                            if (textStatus == 'success') {
                                $('{$this->dependSelector}').trigger('change');
                                $(this).modal('toggle');
                                alert('Данные успешно добавлены.');
                            }
                        }
                    "),
                ModalAjax::EVENT_MODAL_SHOW => new \yii\web\JsExpression("
                        function(event, data, status, xhr, selector) {
                                let depend_value = $('{$this->dependSelector}').val();
                                $('#{$this->formID}-{$field}').val(depend_value);
                        }"
                ),

            ];

        };


    }
}