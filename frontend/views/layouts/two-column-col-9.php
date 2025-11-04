<?php

/* @var $this yii\web\View */
/* @var $content string */

use frontend\widgets\bs5\BreadcrumbWidget;

?>
<?= BreadcrumbWidget::widget([]) ?>

<div class="container-fluid">
    <div class="col-xxl-12">
        <div class="row">

            <div class="col-xl-9">
                <div class="card">
                    <div class="card-body">

                        <?= $content ?>

                    </div>
                </div>
            </div>

            <div class="col-lg-3">

                <button type="button" class="btn mb-3 btn-lg btn-primary w-100">Ask Question</button>

                <div class="card">
                    <div class="card-body border-bottom border-dashed">

                        <h5 class="mb-3 text-uppercase fw-bold">Search</h5>

                        <div class="app-search">
                            <input type="text" class="form-control bg-light-subtle" placeholder="Search issues...">
                            <i data-lucide="search" class="app-search-icon text-muted"></i>
                        </div>

                    </div>

                    <div class="card-body">
                        <h5 class="mb-3 text-uppercase fw-bold">Popular Tags:</h5>
                        <div class="d-flex flex-wrap gap-1">
                            <a class="btn btn-light btn-sm" href="#">Web Design</a>
                            <a class="btn btn-light btn-sm" href="#">Frontend</a>
                            <a class="btn btn-light btn-sm" href="#">Tailwind CSS</a>
                            <a class="btn btn-light btn-sm" href="#">JavaScript</a>
                            <a class="btn btn-light btn-sm" href="#">React</a>
                            <a class="btn btn-light btn-sm" href="#">Startup</a>
                            <a class="btn btn-light btn-sm" href="#">DevTools</a>
                            <a class="btn btn-light btn-sm" href="#">Open Source</a>
                            <a class="btn btn-light btn-sm" href="#">Performance</a>
                            <a class="btn btn-light btn-sm" href="#">UX/UI</a>
                            <a class="btn btn-light btn-sm" href="#">SEO</a>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>

