<?php

/* @var $this View */
/* @var $content string */

use yii\web\View;

?>
    <!-- Begin page -->
    <div class="wrapper">

        <?= $this->render('/layouts/partials/_menu'); ?>

        <div class="content-page">
            <div class="container-fluid">

                <?= $this->render('/layouts/partials/_title', ['subtitle' => 'test']); ?>

                <?= $content ?>

            </div>

            <?= $this->render('/layouts/partials/_footer'); ?>
        </div>

    </div>
    <!-- END wrapper -->

<?= $this->render('/layouts/partials/_customizer'); ?>