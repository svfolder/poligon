<?php

use yii\web\View;

/* @var $this View */
/* @var $content string */
?>

<?= $this->render('/layouts/land/_header'); ?>

<?= $this->render('/layouts/land/_content', ['content' => $content]); ?>

<?= $this->render('/layouts/land/_footer'); ?>