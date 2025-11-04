<?php

/* @var $this yii\web\View */
/* @var $user core\entities\User\User */
/* @var $token core\entities\User\UserToken */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['auth/change/username', 'token' => $token->token_username]);
?>
Hello <?= $user->username ?>,

Follow the link below to reset your username:

<?= $resetLink ?>
