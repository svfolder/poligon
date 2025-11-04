<?php

\Yii::$container->set(\yii\gii\GiiAsset::class, ['class' => \backend\assets\GiiAsset::class]);

\Yii::$container->set(\yii\grid\ActionColumn::class, [
    'contentOptions' => ['style' => 'text-align: center; width: 100px;'],
]);

\Yii::$container->set(\yii\grid\SerialColumn::class, [
    'contentOptions' => ['style' => 'text-align: center; width: 30px;'],
]);

\Yii::$container->set(\yii\grid\CheckboxColumn::class, [
    'contentOptions' => ['style' => 'text-align: center; width: 30px;'],
]);
