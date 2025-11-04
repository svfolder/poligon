<?php


/* @var $this View */

use yii\web\View;
use yii\widgets\Breadcrumbs;

?>
<section class="bg-f5">
    <div class="tf-container">
        <div class="row">
            <div class="col-lg-12">
                <div class="page-title">
                    <div class="widget-menu-link">
                    <?= Breadcrumbs::widget([
                        'links' => $this->params['breadcrumbs'] ?? [],
                        'options' => [
                            'class' => ''
                        ]
                    ]) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>