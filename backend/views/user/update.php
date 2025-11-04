<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model core\entities\User\User */

$this->title = Yii::t('app', 'Update User: {name}', [
    'name' => property_exists($model, 'id') ? $model->id : '',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Users'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="user-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
