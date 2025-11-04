<?php

use frontend\assets\AppAsset;
use frontend\assets\LandingAsset;
use yii\helpers\Html;
use yii\web\View;

/* @var $this View */
/* @var $content string */

\kartik\icons\IcoFontAsset::register($this);

\core\helpers\AssetPublisher::publishImages();
if (Yii::$app->params['layout'] == 'main'){
    AppAsset::register($this);
}else{
    LandingAsset::register($this);
}
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" data-skin="minimal" data-bs-theme="light" data-menu-color="gray" data-topbar-color="light" data-layout-position="fixed" data-sidenav-size="default">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= Html::encode($this->title) ?></title>
    <link rel="shortcut icon" href="/images/favicon.ico">

<?php $this->head() ?>

<?= Html::csrfMetaTags() ?>

</head>

<body class="" >
    <?php $this->beginBody() ?>

<?= $this->render('/layouts/_landing', ['content' => $content]); ?>

    <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>