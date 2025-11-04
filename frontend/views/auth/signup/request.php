<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap5\ActiveForm */
/* @var $model core\forms\auth\SignupForm */

\frontend\assets\AppAsset::register($this);

use frontend\widgets\bs5\BreadcrumbWidget;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

$this->title = Yii::t('auth','Signup');
$this->params['breadcrumbs'][] = $this->title;

$this->registerJsFile('@web/assets/auth-password.js', ['depends' => [\yii\web\JqueryAsset::class]]);
?>
<?= BreadcrumbWidget::widget([]) ?>

        <div class="row justify-content-center">

            <div class="col-xxl-4 col-md-6 col-sm-8">

<?= $this->render('/layouts/partials/_auth_brand', [
    'title' => Yii::t('auth','Register to SV5Kit CRM'),
    'text' => Yii::t('auth','Let’s get you started. Create your account by entering your details below.'),
]); ?>

                <div class="card p-4 rounded-4">

                    <?php $form = ActiveForm::begin(['id' => 'form-signup', 'enableClientValidation' => true]); ?>

                        <?= $form->field($model, 'first_name')->textInput(['autofocus' => true]) ?>

                        <?= $form->field($model, 'last_name')->textInput(['autofocus' => true]) ?>

                        <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                        <?= $form->field($model, 'email') ?>

                        <?= $form->field($model, 'password')->passwordInput() ?>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input form-check-input-light fs-14" type="checkbox" id="termAndPolicy">
                                <label class="form-check-label" for="termAndPolicy">Agree the Terms & Policy</label>
                            </div>
                        </div>

                    <?= Html::submitButton(Yii::t('auth','Signup'), ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>

                    <?php ActiveForm::end(); ?>

                </div>

                <p class="text-center text-muted mt-4 mb-0">
                    © 2014 -
                    <script>document.write(new Date().getFullYear())</script> INSPINIA — by <span class="fw-semibold">WebAppLayers</span>
                </p>

            </div>

        </div>
