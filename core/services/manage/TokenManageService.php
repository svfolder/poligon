<?php


namespace core\services\manage;

use common\components\Dumper;
use core\entities\User\User;
use core\entities\User\UserToken;
use core\forms\PasswordForm;
use core\forms\UserContactForm;
use core\repositories\TokenRepository;
use Yii;
use yii\mail\MailerInterface;


class TokenManageService
{

    private $mailer;
    private $repository;

    public function __construct(TokenRepository $repository, MailerInterface $mailer)
    {
        $this->repository = $repository;
        $this->mailer = $mailer;
    }

    public function makeToken(User $user, UserContactForm $form)
    {
        if ($user->username <> $form->username) {
            $this->makeUsernameToken($user, $form);
        }

        if ($user->email <> $form->email) {
            $this->makeEmailToken($user, $form);
        }
    }

    public function makeTokenPassword(User $user, PasswordForm $form)
    {

        if (!$user->validatePassword($form->password)){
            Yii::$app->session->setFlash('info', Yii::t('auth','Wrong current password'));
        }else{

            $token = $this->repository->findPasswordTokenByUserID($user->id);

            if (!$token) {
                $token = new UserToken();
                $token->user_id = $user->id;
                $token->new_password = Yii::$app->security->generatePasswordHash($form->new_password);
                $token->token_password = Yii::$app->security->generateRandomString();
                $this->repository->save($token);
            }

            $sent = $this->mailer
                ->compose(
                    ['html' => 'auth/change/password-change-html', 'text' => 'auth/change/password-change-text'],
                    ['token' => $token, 'user' => $user]
                )
                ->setTo($user->email)
                ->setSubject('Password change for ' . Yii::$app->name)
                ->send();

            if ($sent) {
                Yii::$app->session->setFlash('info', Yii::t('auth','Check your email address'));
            }else{
                throw new \RuntimeException('Sending error.');
            }

        }

    }

    protected function makeEmailToken(User $user, UserContactForm $form)
    {
        $token = $this->repository->findEmailTokenByUserID($user->id);

        if (!$token) {
            $token = new UserToken();
            $token->user_id = $user->id;
            $token->new_email = $form->email;
            $token->token_email = Yii::$app->security->generateRandomString();
            $this->repository->save($token);
        }

        $sent = $this->mailer
            ->compose(
                ['html' => 'auth/change/email-change-html', 'text' => 'auth/change/email-change-text'],
                ['token' => $token, 'user' => $user]
            )
            ->setTo($user->email)
            ->setSubject('Email change for ' . Yii::$app->name)
            ->send();

        if ($sent) {
            Yii::$app->session->setFlash('info', Yii::t('auth','Check your email address'));
        }else{
            throw new \RuntimeException('Sending error.');
        }

    }

    protected function makeUsernameToken(User $user, UserContactForm $form)
    {
        $token = $this->repository->findUsernameTokenByUserID($user->id);

        if (!$token) {
            $token = new UserToken();
            $token->user_id = $user->id;
            $token->new_username = $form->username;
            $token->token_username = Yii::$app->security->generateRandomString();
            $this->repository->save($token);
        }

        $sent = $this->mailer
            ->compose(
                ['html' => 'auth/change/username-change-html', 'text' => 'auth/change/username-change-text'],
                ['token' => $token, 'user' => $user]
            )
            ->setTo($user->email)
            ->setSubject('Username change for ' . Yii::$app->name)
            ->send();

        //Dumper::dd($this->mailer);
        //Dumper::dd($sent);

        if ($sent) {
            Yii::$app->session->setFlash('info', Yii::t('auth','Check your email address'));
        }else{
            throw new \RuntimeException('Sending error.');
        }

    }

    /**
     * @param string $token
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function changeUsername($token)
    {
        $token = $this->repository->findUsernameToken($token);

        if ($token) {
            $user = User::find()->where(['id' => $token->user_id])->one();
            $user->username = $token->new_username;

            if ($user->save()) {
                $token->delete();
                return true;
            }
        }

    }

    /**
     * @param $token
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function changeEmail($token)
    {
        $token = $this->repository->findEmailToken($token);

        if ($token) {
            $user = User::find()->where(['id' => $token->user_id])->one();
            $user->email = $token->new_email;

            if ($user->save()) {
                $token->delete();
                return true;
            }
        }

    }

    /**
     * @param $token
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function changePassword($token)
    {
        $token = $this->repository->findPasswordToken($token);

        if ($token) {
            $user = User::find()->where(['id' => $token->user_id])->one();
            $user->password_hash = $token->new_password;

            if ($user->save()) {
                $token->delete();
                return true;
            }
        }

    }

    /**
     * @param string $token
     */
    public function validateUsernameToken($token): void
    {
        if (empty($token) || !is_string($token)) {
            throw new \DomainException('Token cannot be blank.');
        }
        if (!$this->repository->findUsernameToken($token)) {
            throw new \DomainException(Yii::t('auth', 'Wrong token.'));
        }
    }

    /**
     * @param string $token
     */
    public function validateEmailToken($token): void
    {
        if (empty($token) || !is_string($token)) {
            throw new \DomainException('Token cannot be blank.');
        }
        if (!$this->repository->findEmailToken($token)) {
            throw new \DomainException(Yii::t('auth', 'Wrong token.'));
        }
    }

    /**
     * @param string $token
     */
    public function validatePasswordToken($token): void
    {
        if (empty($token) || !is_string($token)) {
            throw new \DomainException('Token cannot be blank.');
        }
        if (!$this->repository->findPasswordToken($token)) {
            throw new \DomainException(Yii::t('auth', 'Wrong token.'));
        }
    }

    /**
     * @param integer $id
     */
    public function remove($id): void
    {
        $user = $this->repository->get($id);
        $this->repository->remove($user);
    }

}