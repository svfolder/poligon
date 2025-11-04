<?php

/* @var $this yii\web\View */

use frontend\widgets\bs5\BreadcrumbWidget;
use yii\helpers\Html;

$this->title = Yii::t('app', 'Cabinet');
$this->params['breadcrumbs'][] = $this->title;
?>
<?php $this->beginContent('@frontend/views/layouts/two-column-col-9.php') ?>

                    <h3 class="mb-5"><?= Html::encode($this->title) ?></h3>

                    <h5><?= Yii::t('auth', 'Attach social network:') ?></h5>

                    <?= yii\authclient\widgets\AuthChoice::widget([
                        'baseAuthUrl' => ['cabinet/network/attach'],
                    ]); ?>


<?php $this->endContent() ?>

