<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model core\entities\Auth\AuthItemChild */

$this->title = Yii::t('app', 'Update Auth Item Child: {name}', [
    'name' => property_exists($model, 'parent') ? $model->parent : '',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Auth Item Children'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->parent, 'url' => ['view', 'parent' => $model->parent, 'child' => $model->child]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="auth-item-child-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
