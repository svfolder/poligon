<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model core\forms\auth\ResetPasswordForm */

use frontend\widgets\bs5\BreadcrumbWidget;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = Yii::t('auth','Reset password');
$this->params['breadcrumbs'][] = $this->title;
?>

<?= BreadcrumbWidget::widget([]) ?>
<div class="row justify-content-center">
    <div class="col-xxl-4 col-md-6 col-sm-8">

<?= $this->render('/layouts/partials/_auth_brand', [
    'title' => Yii::t('auth', $this->title),
    'text' => Yii::t('auth','Please choose your new password:'),
]); ?>

        <div class="card p-4 rounded-4">

            <?php $form = ActiveForm::begin(['id' => 'reset-password-form']); ?>

                <?= $form->field($model, 'password')->passwordInput(['autofocus' => true]) ?>

                <?= Html::submitButton(Yii::t('app','Save'), ['class' => 'btn btn-primary']) ?>

            <?php ActiveForm::end(); ?>

        </div>

    </div>
</div>
