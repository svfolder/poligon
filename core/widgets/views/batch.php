<?php

/* @var $this \yii\web\View */
/* @var $model core\entities\ButtonModel */

use kartik\icons\Icon;
\kartik\icons\IcoFontAsset::register($this);

?>
<div class="box box-primary">
    <div class="box-header with-border">
        <h3 class="box-title">Групповые операции над записями.</h3>
    </div>

    <div class="box-body">

        <div class="row">
            <?= \core\widgets\ButtonWidget::widget(
                [
                    'model' => $model,
                    'method' => 'delete',
                    'button_class' => 'btn-danger',
                    'icon' => 'fa fa-remove',
                    'text' => 'Удалить'
                ]
            ) ?>

            <?= \core\widgets\ButtonWidget::widget(
                [
                    'model' => $model,
                    'method' => 'enable',
                    'button_class' => 'btn-success',
                    'icon' => 'icofont icofont-toggle-on',
                    'text' => 'Включить'
                ]
            ) ?>

            <?= \core\widgets\ButtonWidget::widget(
                [
                    'model' => $model,
                    'method' => 'disable',
                    'button_class' => 'default',
                    'icon' => 'icofont icofont-toggle-off',
                    'text' => 'Выключить'
                ]
            ) ?>

            <?= \core\widgets\ButtonWidget::widget(
                [
                    'model' => $model,
                    'method' => 'unban',
                    'button_class' => 'btn-success',
                    'icon' => 'fa fa-unlock',
                    'text' => 'Разблокировать'
                ]
            ) ?>

            <?= \core\widgets\ButtonWidget::widget(
                [
                    'model' => $model,
                    'method' => 'ban',
                    'button_class' => 'btn-warning',
                    'icon' => 'fa fa-lock',
                    'text' => 'Заблокировать'
                ]
            ) ?>

            <?= \core\widgets\ButtonWidget::widget(
                [
                    'model' => $model,
                    'method' => 'online',
                    'button_class' => 'btn-success',
                    'icon' => 'icofont icofont-ui-network',
                    'text' => 'Онлайн'
                ]
            ) ?>

            <?= \core\widgets\ButtonWidget::widget(
                [
                    'model' => $model,
                    'method' => 'offline',
                    'button_class' => 'btn-warning',
                    'icon' => 'fa fa-lock',
                    'text' => 'Оффлайн'
                ]
            ) ?>

        </div>

    </div>

</div>