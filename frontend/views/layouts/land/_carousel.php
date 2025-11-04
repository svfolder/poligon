<?php

use yii\web\View;

/* @var $this View */
/* @var $content string */
?>

<section class="section-custom" id="carousel">

    <div class="container">
        <div class="row">

            <div id="carouselExampleCaption" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner" role="listbox">
                    <div class="carousel-item active">
                        <!-- first slide  -->
                        <img src="/images/stock/small-1.jpg" alt="..." class="d-block img-fluid">
                        <div class="carousel-caption d-none d-md-block">
                            <h3 class="text-white">First slide label</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <!-- secound slide  -->
                        <img src="/images/stock/small-3.jpg" alt="..." class="d-block img-fluid">
                        <div class="carousel-caption d-none d-md-block">
                            <h3 class="text-white">Second slide label</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <!-- third slide  -->
                        <img src="/images/stock/small-2.jpg" alt="..." class="d-block img-fluid">
                        <div class="carousel-caption d-none d-md-block">
                            <h3 class="text-white">Third slide label</h3>
                            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                        </div>
                    </div>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleCaption" role="button" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleCaption" role="button" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </a>
            </div>

        </div>
    </div>

</section>
