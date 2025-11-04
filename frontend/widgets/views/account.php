<?php

/* @var $this View */

use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

\kartik\icons\IcoFontAsset::register($this);

if (!Yii::$app->user->isGuest){
    /** @var User $user */
    $user = Yii::$app->user->identity->getUser();
}
?>

<?php if (Yii::$app->user->isGuest): ?>

<?php else: ?>
    <img src="/images/user/avatar/default.jpg" alt=""/>
    <div class="name">
        <?= $user->username; ?><span class="icon-keyboard_arrow_down"></span>
    </div>
    <div class="sub-account">
        <div class="sub-account-item">
            <a href="<?= Html::encode(Url::to(['/cabinet/index'])) ?>">
                <span class="icon-profile"></span>
                <?=Yii::t('app','Profile')?>
            </a>
        </div>
        <div class="sub-account-item">
            <a href="<?= Html::encode(Url::to(['/auth/auth/logout'])) ?>" data-method="post">
                <span class="icon-log-out"></span>
                <?=Yii::t('app','Logout')?>
            </a>
        </div>
    </div>
<?php endif; ?>