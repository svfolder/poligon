<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap5\ActiveForm */
/* @var $model core\forms\auth\PasswordResetRequestForm */

use frontend\widgets\bs5\BreadcrumbWidget;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = Yii::t('auth','Request password reset');
$this->params['breadcrumbs'][] = $this->title;
?>
<?= BreadcrumbWidget::widget([]) ?>

<div class="row justify-content-center">
    <div class="col-xxl-4 col-md-6 col-sm-8">

<?= $this->render('/layouts/partials/_auth_brand', [
    'title' => Yii::t('auth', $this->title),
    'text' => Yii::t('auth','Please fill out your email. A link to reset password will be sent there.'),
]); ?>

        <div class="card p-4 rounded-4">

            <?php $form = ActiveForm::begin(['id' => 'request-password-reset-form']); ?>

                <?= $form->field($model, 'email')->textInput(['autofocus' => true]) ?>

                <?= Html::submitButton(Yii::t('app','Send'), ['class' => 'btn btn-primary']) ?>

            <?php ActiveForm::end(); ?>

        </div>

    </div>
</div>