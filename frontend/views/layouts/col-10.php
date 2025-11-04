<?php

/* @var $this yii\web\View */
/* @var $content string */

use frontend\widgets\bs5\BreadcrumbWidget;

?>
<?= BreadcrumbWidget::widget([]) ?>
<div class="row justify-content-center">
    <div class="col-xxl-10 col-md-6 col-sm-8">

<?= $content ?>

<?= $this->render('/layouts/partials/_copyright', []); ?>

    </div>
</div>

