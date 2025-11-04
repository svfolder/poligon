<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model core\entities\Menu\MenuItem */

$this->title = Yii::t('app', 'Create Menu Item');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Menu Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-item-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
