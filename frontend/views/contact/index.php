<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap5\ActiveForm */
/* @var $model core\forms\ContactForm */

use frontend\widgets\bs5\BreadcrumbWidget;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\captcha\Captcha;

$this->title = Yii::t('app','Contact');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginContent('@frontend/views/layouts/col-4.php') ?>

<?= $this->render('/layouts/partials/_auth_brand', [
    'title' => Yii::t('auth',$this->title),
    'text' => Yii::t('app','If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.'),
]); ?>

            <div class="card p-4 rounded-4">

                <?php $form = ActiveForm::begin(['id' => 'contact-form']); ?>

                    <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>

                    <?= $form->field($model, 'email') ?>

                    <?= $form->field($model, 'subject') ?>

                    <?= $form->field($model, 'body')->textarea(['rows' => 6]) ?>

                    <?= $form->field($model, 'verifyCode')->widget(Captcha::className(), [
                        'template' => '<div class="row"><div class="col-lg-3">{image}</div><div class="col-lg-6">{input}</div></div>',
                    ]) ?>

                    <?= Html::submitButton(Yii::t('app','Submit'), ['class' => 'btn btn-primary', 'name' => 'contact-button']) ?>

                <?php ActiveForm::end(); ?>

            </div>

<?php $this->endContent() ?>