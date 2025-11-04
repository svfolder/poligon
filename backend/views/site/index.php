<?php

/* @var $this yii\web\View */

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = Yii::$app->name;
?>
<div class="site-index">


    <div class="body-content">

        <div class="row">

            <div class="col-lg-3">

                <?php \insolita\wgadminlte\LteBox::begin([
                    'type' => \insolita\wgadminlte\LteConst::TYPE_PRIMARY,
                    'isSolid' => true,
                    'boxTools' => '',
                    'tooltip' => '',
                    'title' => 'Ручное управление',
                    'footer' => 'предназначено для админов',
                ]) ?>
                <?= Html::a('Create Rules',
                    Url::to('site/rule'),
                    ['target' => "__blank", 'class' => 'btn btn-success btn-xs'])
                ?>

                <?= Html::a('Collect',
                    Url::to('site/collect'),
                    ['target' => "__blank", 'class' => 'btn btn-success btn-xs'])
                ?>

                <?php \insolita\wgadminlte\LteBox::end() ?>

            </div>

        </div>

    </div>
</div>
