<?php

/* @var $this yii\web\View */

use frontend\widgets\bs5\BreadcrumbWidget;

$this->title = Yii::$app->name;
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginContent('@frontend/views/layouts/two-column-col-9.php') ?>

<h3 class="mb-3">Поздравляем!</h3>
<p class="lead mb-5">Вы успешно подняли веб приложение на базе сборки "SV5KIT" (Yii2 Framework) .</p>

<?php $this->endContent() ?>