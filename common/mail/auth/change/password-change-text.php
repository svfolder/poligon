<?php

/* @var $this yii\web\View */
/* @var $user core\entities\User\User */
/* @var $token core\entities\User\UserToken */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['auth/change/password', 'token' => $token->token_password]);
?>
Hello <?= $user->username ?>,

Follow the link below to change your password:

<?= $resetLink ?>
