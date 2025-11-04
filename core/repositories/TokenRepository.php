<?php


namespace core\repositories;


use core\entities\User\UserToken;


class TokenRepository
{


    public function get($id): UserToken
    {
        return $this->getBy(['id' => $id]);
    }

    /**
     * @param integer $user_id
     * @return array|UserToken|null
     */
    public function findEmailTokenByUserID($user_id)
    {
        return UserToken::find()->andWhere(['user_id' => $user_id])->andWhere(['not', ['new_email' => null]])->limit(1)->one();
    }

    /**
     * @param $user_id
     * @return array|UserToken|null
     */
    public function findPasswordTokenByUserID($user_id)
    {
        return UserToken::find()->andWhere(['user_id' => $user_id])->andWhere(['not', ['new_password' => null]])->limit(1)->one();
    }

    /**
     * @param integer $user_id
     * @return array|UserToken|null
     */
    public function findUsernameTokenByUserID($user_id)
    {
        return UserToken::find()->andWhere(['user_id' => $user_id])->andWhere(['not', ['new_username' => null]])->limit(1)->one();
    }

    /**
     * @param string $token
     * @return array|UserToken|null
     */
    public function findUsernameToken($token)
    {
        return UserToken::find()->andWhere(['token_username' => $token])->limit(1)->one();
    }

    /**
     * @param string $token
     * @return array|UserToken|null
     */
    public function findEmailToken($token)
    {
        return UserToken::find()->andWhere(['token_email' => $token])->limit(1)->one();
    }

    /**
     * @param string $token
     * @return array|UserToken|null
     */
    public function findPasswordToken($token)
    {
        return UserToken::find()->andWhere(['token_password' => $token])->limit(1)->one();
    }

    public function getByUserID($user_id): UserToken
    {
        return $this->getBy(['user_id' => $user_id]);
    }

    public function getByToken($token): UserToken
    {
        return $this->getBy(['token' => $token]);
    }

    private function getBy(array $condition): UserToken
    {
        if (!$token = UserToken::find()->andWhere($condition)->limit(1)->one()) {
            throw new NotFoundException('Token not found.');
        }
        return $token;
    }

    public function remove(UserToken $token): void
    {
        if (!$token->delete()) {
            throw new \RuntimeException('Removing error.');
        }
    }

    public function save(UserToken $token): void
    {
        if (!$token->save()) {
            throw new \RuntimeException('Saving error.');
        }
    }

}