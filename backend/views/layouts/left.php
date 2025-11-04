<?php

$menus = require __DIR__ . '/_menu.php';

use cebe\gravatar\Gravatar;
use core\entities\User\User;
use core\helpers\MenuHelper;

/** @var User $user */
$user = Yii::$app->user->identity->getUser();

//use kartik\icons\Icon;
//\kartik\icons\IcoFontAsset::register($this);

?>
<aside class="main-sidebar">

    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <?= Gravatar::widget([
                    'email' => $user->email,
                    'options' => [
                        'alt' => $user->username,
                        'class' => 'img-circle'
                    ],
                    'size' => 64,
                ]) ?>
            </div>
            <div class="pull-left info">
                <p><?php echo $user->username; ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>

<?//= $this->render('_search') ?>

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu', 'iconClassPrefix' => ''],
                'items' => MenuHelper::merge([
                    ['label' => Yii::t('app','Management'), 'options' => ['class' => 'header']],


                ], $menus)
            ]
        ) ?>

    </section>

</aside>
