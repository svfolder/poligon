<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model core\entities\Auth\AuthItem */

$this->title = Yii::t('app', 'Create Auth Item');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Auth Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
