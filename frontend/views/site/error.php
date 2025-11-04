<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use frontend\widgets\bs5\BreadcrumbWidget;
use yii\helpers\Html;

$this->title = $name;
$this->params['breadcrumbs'][] = $this->title;
?>
<?= BreadcrumbWidget::widget([]) ?>
<div class="row justify-content-center">
    <div class="col-xxl-4 col-md-6 col-sm-8">

<?= $this->render('/layouts/partials/_auth_brand', [
    'title' => Yii::t('auth', $this->title),
    'text' => Yii::t('auth','Something`s not right in the request you made.'),
]); ?>

        <div class="alert alert-danger mb-5">
            <?= nl2br(Html::encode($message)) ?>
        </div>

    </div>
</div>