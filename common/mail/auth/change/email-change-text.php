<?php

/* @var $this yii\web\View */
/* @var $user core\entities\User\User */
/* @var $token core\entities\User\UserToken */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['auth/change/email', 'token' => $token->token_email]);
?>
Hello <?= $user->username ?>,

Follow the link below to change your email:

<?= $resetLink ?>
