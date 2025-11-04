<?php

use yii\web\View;

/* @var $this View */
/* @var $content string */

?>
    <header>
        <nav class="navbar navbar-expand-lg py-3 sticky-top" id="landing-navbar">
            <div class="container">
                <div class="auth-brand mb-0">
                    <a href="/" class="logo-dark">
                        <img src="/images/logo-black.png" alt="dark logo" height="32">
                    </a>
                    <a href="/" class="logo-light">
                        <img src="/images/logo.png" alt="logo" height="32">
                    </a>
                </div>

<?= $this->render('/layouts/land/_navbar'); ?>

            </div>
        </nav>

    </header>
