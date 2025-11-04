<?php

use core\helpers\RoleHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model core\entities\Auth\AuthItemChild */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="auth-item-child-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box box-default">
        <div class="box-header with-border"></div>
        <div class="box-body">

            <?= $form->errorSummary($model, ['class' => 'callout callout-danger']); ?>

            <?= $form->field($model, 'parent')->dropDownList(RoleHelper::rolesList()) ?>

            <?= $form->field($model, 'child')->dropDownList(RoleHelper::rolesList()) ?>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>