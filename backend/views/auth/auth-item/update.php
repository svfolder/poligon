<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model core\entities\Auth\AuthItem */

$this->title = Yii::t('app', 'Update Auth Item: {name}', [
    'name' => property_exists($model, 'name') ? $model->name : '',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Auth Items'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'name' => $model->name]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="auth-item-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
