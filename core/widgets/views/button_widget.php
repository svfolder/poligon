<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this \yii\web\View */
/* @var $model core\entities\ButtonModel */
/* @var $method string */
/* @var $icon string */
/* @var $class string */
/* @var $text string */

?>
<?php $form = ActiveForm::begin(['action' => ["batch-$method"], 'method' => 'post', 'options' => ['class' => 'pull-left m-1']]); ?>
<?= $form->field($model, 'data')->hiddenInput(['id' => "data-{$method}"])->label(false) ?>
<?= Html::submitButton( "<i class='{$icon}'></i> {$text}", ['class' => "btn btn-sm {$class}", 'id' => "{$method}"]) ?>
<?php ActiveForm::end(); ?>

<?php $this->registerJs("
$(document).ready(function() {
    $('#". $method ."').on('click',function (e) {
        var checked = [];
        $('.grid-view tbody input:checkbox:checked').each(function(){
            checked.push($(this).val());
        });
        $('#data-". $method . "').val(checked); 
    });
});
"); ?>