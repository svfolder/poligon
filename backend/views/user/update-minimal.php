<?php

/* @var $this yii\web\View */
/* @var $model core\forms\manage\User\UserEditForm */
/* @var $user core\entities\User\User */

use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$this->title = 'Update User: ' . $user->id;
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $user->id, 'url' => ['view', 'id' => $user->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="user-update">

    <?php $form = ActiveForm::begin(); ?>

    <div class="box box-default">
        <div class="box-header with-border"></div>
        <div class="box-body">

            <?= $form->errorSummary($model, ['class' => 'callout callout-danger']); ?>

            <fieldset class="section" id="general">

                <?= $form->field($model, 'username')->textInput(['maxLength' => true]) ?>

                <?= $form->field($model, 'email')->textInput(['maxLength' => true]) ?>

                <?= $form->field($model, 'first_name')->textInput(['maxLength' => true]) ?>

                <?= $form->field($model, 'last_name')->textInput(['maxLength' => true]) ?>

                <?= $form->field($model, 'is_banned')->checkbox() ?>

                <?= $form->field($user, 'image')->fileInput() ?>

                <?php if ($user->image):?>
                    <div>
                        <img src='<?= $user->getThumbFileUrl('image', 'default') ?>' alt='Изображение'/>
                    </div>
                <?php endif;?>

                <?= $form->field($model, 'role')->dropDownList($model->rolesList()) ?>

            </fieldset>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
