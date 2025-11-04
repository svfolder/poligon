<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model core\forms\Menu\MenuItemForm */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="menu-item-form">

    <?php $form = ActiveForm::begin(['id' => 'menu-item-form']); ?>

    <div class="box box-default">
        <div class="box-header with-border"></div>
        <div class="box-body">

        <?= $form->errorSummary($model, ['class' => 'callout callout-danger']); ?>

        <fieldset class="section" id="general">
            <?= $form->field($model, 'parent_id')->dropdownList(
                    yii\helpers\ArrayHelper::merge(['' => 'Выбрать из списка'],
                    \core\entities\Menu\MenuItem::find()->active()->select(['name', 'id'])->indexBy('id')->column())
            ) ?>
            <?= $form->field($model, 'menu_id')->dropdownList(
                    yii\helpers\ArrayHelper::merge(['0' => 'Выбрать из списка'],
                    \core\entities\Menu\Menu::find()->active()->select(['name', 'id'])->indexBy('id')->column())
            ) ?>
            <?= $form->field($model, 'name')->textInput(['maxlength' => true, 'autofocus' => true]) ?>
            <?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'status')->checkbox() ?>

        </fieldset>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('<i class="fa fa-save"></i> ' . Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>