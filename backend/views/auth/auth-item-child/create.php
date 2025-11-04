<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model core\entities\Auth\AuthItemChild */

$this->title = Yii::t('app', 'Create Auth Item Child');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Auth Item Children'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-child-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
