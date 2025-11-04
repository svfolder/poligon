<?php

/* @var $this yii\web\View */

use frontend\widgets\bs5\BreadcrumbWidget;
use yii\helpers\Html;

$this->title = Yii::t('app','About');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginContent('@frontend/views/layouts/two-column-col-9.php') ?>

    <h3 class="mb-3"><?= Html::encode($this->title) ?></h3>

    <p>This is the About page. You may modify the following file to customize its content:</p>

    <code><?= __FILE__ ?></code>


<?php $this->endContent() ?>