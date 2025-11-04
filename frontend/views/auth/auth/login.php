<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model core\forms\auth\LoginForm */

\frontend\assets\AppAsset::register($this);

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('app','Login');
$this->params['breadcrumbs'][] = $this->title;

?>
<?php $this->beginContent('@frontend/views/layouts/col-4.php') ?>

<?= $this->render('/layouts/partials/_auth_brand', [
    'title' => Yii::t('auth','Welcome to SV5Kit CRM'),
    'text' => Yii::t('auth','Please fill out the following fields to login.'),
]); ?>

    <div class="card p-4 rounded-4">

        <?php $form = ActiveForm::begin(['id' => 'login-form', 'enableClientValidation' => true]); ?>

        <?= $form->errorSummary($model, ['class' => 'callout callout-danger']); ?>

        <?= $form->field($model, 'username', [
            'template' => '{label}<div class="input-group has-validation">{input}{error}</div>',
        ])->textInput(['autofocus' => true]) ?>

        <?= $form->field($model, 'password', [])->passwordInput() ?>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <div class="form-check">

                <?= Html::activeCheckbox($model, 'rememberMe', [
                    'id' => 'rememberMe',
                    'class' => 'form-check-input form-check-input-light fs-14',
                    'label' => null,
                    'uncheck' => 0,
                ]) ?>

                <label class="form-check-label" for="rememberMe"><?= Yii::t('app','RememberMe') ?></label>

            </div>

            <?= Html::a(Yii::t('auth','Forgot password?'), ['auth/reset/request'], ['class' => 'forgot text-decoration-underline link-offset-3 text-muted']) ?>
        </div>

        <div class="d-grid">
            <?= Html::submitButton(Yii::t('app','Login'), ['class' => 'btn btn-primary fw-semibold py-2', 'name' => 'login-button']) ?>
        </div>

        <?php ActiveForm::end(); ?>

        <p class="text-muted text-center mt-4 mb-0">
            <?= Yii::t('auth','New here?') ?>
            <a href="<?= Html::encode(Url::to(['/auth/signup/request'])) ?>" class="text-decoration-underline link-offset-3 fw-semibold">
                <?=Yii::t('app','Signup')?>
            </a>
        </p>
    </div>

<?php $this->endContent() ?>