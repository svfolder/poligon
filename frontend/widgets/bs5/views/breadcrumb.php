<?php


/* @var $this View */

use yii\web\View;
use yii\widgets\Breadcrumbs;

?>
<nav aria-label="breadcrumb" class="pb-3">

<?= Breadcrumbs::widget([
    'links' => $this->params['breadcrumbs'] ?? [],
    'tag' => 'ol',
    'itemTemplate' => "\n<li class='breadcrumb-item'>{link}</li>",
    'activeItemTemplate' => "\n<li class='breadcrumb-item active' aria-current='page'>{link}</li>",
    'options' => [
        'class' => 'breadcrumb bg-light bg-opacity-50 p-2 mb-0'
    ]
]) ?>

</nav>
