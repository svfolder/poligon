<?php

$menuDeveloper = require __DIR__ . '/_developer.php';

use common\components\LanguageSwitcher;
use yii\bootstrap\Nav;
use yii\helpers\Html;

use cebe\gravatar\Gravatar;
use core\entities\User\User;
use yiiui\yii2flagiconcss\assets\FlagIconCssAsset;

/** @var User $user */
$user = Yii::$app->user->identity->getUser();

/** @var yii\web\UrlManager $frontend */
if (isset(Yii::$app->frontendUrlManager)) {
    $frontend = Yii::$app->frontendUrlManager;
}

FlagIconCssAsset::register($this);

/* @var $this yii\web\View */
/* @var $content string */
?>

<header class="main-header">

    <?= Html::a('<span class="logo-mini">APP</span><span class="logo-lg">' . Yii::$app->name . '</span>', Yii::$app->homeUrl, ['class' => 'logo']) ?>

    <nav class="navbar navbar-static-top" role="navigation">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="container-fluid">

            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse pull-left" id="navbar-collapse">

                <?php
                $menuItems = [
                    ['label' => Yii::t('app','Home'), 'icon' => 'fa fa-home', 'url' => ['/site/index']],

                    [
                        'label' => 'RBAC', 'icon' => 'icofont icofont-users', 'header' => true, 'url' => ['/auth-item/index'],
                        'items' => [
                            ['label' => Yii::t('app','Users'), 'icon' => 'icofont icofont-users', 'url' => ['/user/index'], 'active' => $this->context->id == 'user/index'],
                            ['label' => Yii::t('app','Auth Items'), 'icon' => 'icofont icofont-safety', 'url' => ['/auth/auth-item/index']],
                            ['label' => Yii::t('app','Auth Item Children'), 'icon' => 'icofont icofont-safety', 'url' => ['/auth/auth-item-child/index']],
                        ]
                    ],

                    [
                        'label' => Yii::t('app','Languages'), 'icon' => 'fa fa-language', 'header' => true, 'url' => ['/language/index'],
                        'items' => [
                            ['label' => Yii::t('app','Languages'), 'icon' => 'fa fa-language', 'url' => ['/language/index']],
                        ]
                    ],

                    [
                        'label' => Yii::t('app','Menu'), 'icon' => 'icofont icofont-navigation-menu', 'header' => true, 'url' => ['/menu/index'],
                        'items' => [
                            ['label' => Yii::t('app','Menu'), 'icon' => 'icofont icofont-navigation-menu', 'url' => ['/menu/index']],
                            ['label' => Yii::t('app','Menu Items'), 'icon' => 'icofont icofont-list', 'url' => ['/menu-item/index']],
                        ]
                    ],

                ];

                $user->hasRole('developer') ? $menuItems[] = $menuDeveloper : null;

                echo Nav::widget(['encodeLabels' => false, 'options' => ['class' => 'nav navbar-nav'], 'items' => $menuItems,]);
                ?>

            </div>
            <!-- /.navbar-collapse -->

            <?= LanguageSwitcher::widget(); ?>

            <form class="navbar-form navbar-left hidden-xs hidden-sm" role="search">
                <div class="form-group">
                    <input type="text" class="form-control" id="navbar-search-input" placeholder="<?=Yii::t('app','Search')?>">
                </div>
            </form>

            <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="<?= $frontend->createAbsoluteUrl(['/']) ?>" target="_blank" class="btn btn-xs btn-primary">
                            <i class="fa fa-external-link"></i>
                        </a>
                    </li>
                </ul>
            </div>

            <div class="navbar-custom-menu pull-left">

                <ul class="nav navbar-nav">

                    <li class="dropdown user user-menu hidden-sm">

                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <?= Gravatar::widget([
                                'email' => $user->email,
                                'options' => [
                                    'alt' => $user->username,
                                    'class' => 'img-circle'
                                ],
                                'size' => 32,
                            ]) ?>
                            <span class="hidden-xs"><?php echo $user->username; ?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- User image -->
                            <li class="user-header">
                                <?= Gravatar::widget([
                                    'email' => $user->email,
                                    'options' => [
                                        'alt' => $user->username,
                                        'class' => 'img-circle'
                                    ],
                                    'size' => 64,
                                ]) ?>
                                <p>
                                    <?php echo $user->username; ?>
                                    <small>Member since <?=Yii::$app->formatter->asDate($user->created_at, 'long'); ?> </small>
                                </p>
                            </li>
                            <!-- Menu Body -->

                            <!-- Menu Footer-->
                            <li class="user-footer">
                                <div class="pull-right">
                                    <?= Html::a(
                                        'Выход',
                                        ['/auth/logout'],
                                        ['data-method' => 'post', 'class' => 'btn btn-default btn-flat']
                                    ) ?>
                                </div>
                            </li>

                        </ul>
                    </li>

                </ul>
            </div>

            <div class="navbar-header pull-right">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                    <i class="fa fa-bars"></i>
                </button>
            </div>

        </div>

    </nav>

</header>
