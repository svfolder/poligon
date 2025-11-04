<?php

/* @var $this yii\web\View */
/* @var $model core\forms\manage\User\UserCreateForm */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = Yii::t('app','Create User');
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box box-default">
        <div class="box-header with-border"></div>
        <div class="box-body">

            <?= $form->errorSummary($model, ['class' => 'callout callout-danger']); ?>

            <fieldset class="section" id="general">

                <?= $form->field($model, 'username')->textInput(['maxLength' => true]) ?>

                <?= $form->field($model, 'email')->textInput(['maxLength' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput(['maxLength' => true]) ?>

                <?= $form->field($model, 'role')->dropDownList($model->rolesList()) ?>

                <?= $form->field($model, 'is_banned')->checkbox() ?>

            </fieldset>
        </div>
    </div>
    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
