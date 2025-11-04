<?php

use yii\bootstrap5\Nav;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

/* @var $this View */
/* @var $content string */

?>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">

<?= Nav::widget([
    'options' => ['class' => 'navbar-nav fw-medium gap-2 fs-sm mx-auto mt-2 mt-lg-0'],
    'id' => 'navbar-example',
    'items' => [
        ['label' => Yii::t('app','Home'), 'url' => ['/site/index']],
        ['label' => Yii::t('app','About'), 'url' => ['/site/about']],
        ['label' => Yii::t('app','Article'), 'url' => ['/site/article']],
        ['label' => Yii::t('app','Contact'), 'url' => ['/contact/index']],
        !Yii::$app->user->isGuest ? ['label' => Yii::t('app','Cabinet'), 'url' => Url::to(['/cabinet/default/index'])] : ['label' => '']
    ],
]) ?>


                    <div>
                        <button class="btn btn-link btn-icon fw-semibold text-body" type="button" id="theme-toggle">
                            <i class="ti ti-contrast fs-22"></i>
                        </button>

<?php if (Yii::$app->user->isGuest): ?>
                        <a href="<?= Html::encode(Url::to(['/auth/auth/login'])) ?>" class="btn btn-link fw-semibold text-body ps-2">
                            <?=Yii::t('app','Login')?>
                        </a>
                        <a href="<?= Html::encode(Url::to(['/auth/signup/request'])) ?>" class="btn btn-sm btn-primary">
                            <?=Yii::t('app','Signup')?>
                        </a>
<?php else: ?>
                        <a href="<?= Html::encode(Url::to(['/auth/auth/logout'])) ?>" class="btn btn-sm btn-warning"><?=Yii::t('app','Logout')?>&nbsp;
                                <i data-lucide="log-out" class="fs-lg"></i>
                        </a>
<?php endif; ?>

                    </div>

                </div>

