<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model core\entities\Menu\MenuItem */

$this->title = Yii::t('app', 'Update Menu Item: {name}', [
    'name' => property_exists($model, 'name') ? $model->name : '',
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Menu Items'), 'url' => ['index']];
//$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="menu-item-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
