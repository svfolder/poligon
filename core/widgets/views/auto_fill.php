<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $ids string[] */

$self = array_shift($ids);

$language_fields = implode(', ',
    array_map(function ($value) {
        return "#{$value}";
    }, $ids)
);

?>
    <label>
        Автоматически заполнить все языки:
        <input name="<?= $name ?>" id="<?= $name ?>" type="checkbox" checked="checked">
    </label>
<?php
$script = <<< JS

    $('#{$self}').on('keyup', function() {
        if ($('#{$name}').is(':checked')){
            $('{$language_fields}').val($(this).val());
        }
    });

    $('#{$name}').on('change', function() {
        if ($(this).is(':checked')){
            $('{$language_fields}').val($('#{$self}').val());
        }else {
            $('{$language_fields}').val('');
        }
    });

JS;

$this->registerJs($script, \yii\web\View::POS_END, 'key' . $name);
?>