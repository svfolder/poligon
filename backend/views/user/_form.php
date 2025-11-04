<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model core\forms\User\UserForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(['id' => 'user-form']); ?>

    <div class="box box-default">
        <div class="box-header with-border"></div>
        <div class="box-body">

        <?= $form->errorSummary($model, ['class' => 'callout callout-danger']); ?>

        <fieldset class="section" id="general">
            <?= $form->field($model, 'username')->textInput(['maxlength' => true, 'autofocus' => true]) ?>
            <?= $form->field($model, 'first_name')->textInput(['maxlength' => true, 'autofocus' => true]) ?>
            <?= $form->field($model, 'last_name')->textInput(['maxlength' => true, 'autofocus' => true]) ?>

            <?= $form->field($model, 'image')->fileInput() ?>

            <?php if ($model->dataModel()->image):?>
                <div>
                    <img src='<?= $model->dataModel()->getThumbFileUrl('image', 'default') ?>' alt='Изображение'/>
                </div>
            <?php endif;?>

            <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'is_banned')->checkbox() ?>
            <?= $form->field($model, 'role')->dropDownList(\core\helpers\UserHelper::rolesList()) ?>

        </fieldset>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('<i class="fa fa-save"></i> ' . Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>