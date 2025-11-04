<?php

namespace core\traits;

use core\entities\User\User;
use core\entities\User\UserNetwork;
use Yii;
use yii\base\Exception;

trait UserTrait
{
    protected $_time_online = 900;

    /**
     * @throws Exception
     */
    public static function createMinimal(
        string $username,
        string $email,
        string $password,
        $first_name = null,
        $last_name = null,
        $is_banned = 0
    ): self
    {
        $user = new static();
        $user->username = $username;
        $user->email = $email;
        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->setPassword(!empty($password) ? $password : Yii::$app->security->generateRandomString());
        $user->created_at = time();
        $user->status = self::STATUS_ACTIVE;
        $user->auth_key = Yii::$app->security->generateRandomString();
        $user->is_banned = $is_banned;
        return $user;
    }

    public function editMinimal(
        string $username,
        string $email,
        $first_name,
        $last_name,
        $image,
        $is_banned = 0
    ): self
    {
        $this->username = $username;
        $this->email = $email;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->image = $image;
        $this->is_banned = $is_banned;
        $this->updated_at = time();
        return $this;
    }


    public function transactions(): array
    {
        return [
            self::SCENARIO_DEFAULT => self::OP_ALL,
        ];
    }

    /**
     * @throws Exception
     */
    public static function signup(string $username, string $email, string $password, $first_name, $last_name): self
    {
        $user = new static();
        $user->username = $username;
        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->email = $email;
        $user->setPassword($password);
        $user->created_at = time();
        $user->status = self::STATUS_ACTIVE;
        $user->generateAuthKey();
        return $user;
    }

    /**
     * @throws Exception
     */
    public static function requestSignup(string $username, string $email, string $password, $first_name, $last_name): self
    {
        $user = new static();
        $user->username = $username;
        $user->first_name = $first_name;
        $user->last_name = $last_name;
        $user->email = $email;
        $user->setPassword($password);
        $user->created_at = time();
        $user->status = self::STATUS_WAIT;
        $user->email_confirm_token = Yii::$app->security->generateRandomString();
        $user->generateAuthKey();
        return $user;
    }

    public function confirmSignup(): void
    {
        if (!$this->isWait()) {
            throw new \DomainException(Yii::t('auth','User is already active.'));
        }
        $this->status = self::STATUS_ACTIVE;
        $this->email_confirm_token = null;
    }

    /**
     * @throws Exception
     */
    public static function signupByNetwork($network, $identity): self
    {
        $user = new static();
        $user->created_at = time();
        $user->status = self::STATUS_ACTIVE;
        $user->generateAuthKey();
        $user->userNetworks = [UserNetwork::create($network, $identity)];
        return $user;
    }

    /**
     * @throws Exception
     */
    public function requestPasswordReset(): void
    {
        if (!empty($this->password_reset_token) && self::isPasswordResetTokenValid($this->password_reset_token)) {
            throw new \DomainException(Yii::t('auth','Password resetting is already requested.'));
        }
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * @throws Exception
     */
    public function resetPassword($password): void
    {
        if (empty($this->password_reset_token)) {
            throw new \DomainException('Password resetting is not requested.');
        }
        $this->setPassword($password);
        $this->password_reset_token = null;
    }

    public static function findByUsername(string $username): ?UserTrait
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    public static function findByPasswordResetToken(string $token): ? User
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    public static function isPasswordResetTokenValid(string $token): bool
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    public function validatePassword(string $password): bool
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * @throws Exception
     */
    private function setPassword(string $password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * @throws Exception
     */
    private function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }

    public function hasRole($roleName): bool
    {
        $authManager = \Yii::$app->getAuthManager();
        return (bool)$authManager->getAssignment($roleName, $this->id);
    }

    public function hasParentRole(string $targetRole): bool
    {
        $authManager = Yii::$app->getAuthManager();
        $userRoles = $authManager->getRolesByUser($this->id);

        foreach ($userRoles as $role) {
            if ($this->hasRoleInherited($authManager, $role->name, $targetRole)) {
                return true;
            }
        }

        return false;
    }

    private function hasRoleInherited($authManager, string $currentRole, string $targetRole, array &$visited = []): bool
    {
        if (in_array($currentRole, $visited)) {
            return false;
        }
        $visited[] = $currentRole;

        if ($currentRole === $targetRole) {
            return true;
        }

        $parents = $authManager->getChildren($currentRole);
        foreach ($parents as $parent) {
            if ($this->hasRoleInherited($authManager, $parent->name, $targetRole, $visited)) {
                return true;
            }
        }

        return false;
    }

    public function getAllParentRoles(): array
    {
        $authManager = Yii::$app->getAuthManager();
        $userRoles = $authManager->getRolesByUser($this->id);
        $result = [];

        foreach ($userRoles as $role) {
            $this->collectAllParentRoles($authManager, $role->name, $result);
        }

        return array_unique($result);
    }

    private function collectAllParentRoles(
        $authManager,
        string $currentRole,
        array &$result,
        array &$visited = []
    ): void {

        if (in_array($currentRole, $visited)) {
            return;
        }
        $visited[] = $currentRole;

        foreach ($authManager->getChildren($currentRole) as $child) {
            if ($child->type == \yii\rbac\Item::TYPE_ROLE) {
                $result[] = $child->name;
                $this->collectAllParentRoles($authManager, $child->name, $result, $visited);
            }
        }
    }

    public function isWait(): bool
    {
        return $this->status === self::STATUS_WAIT;
    }

    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isBanned(): bool
    {
        return $this->is_banned === 1;
    }

    public function isOnline(): bool
    {
        $time_online_ago = time() - $this->_time_online;
        return $this->activity_at >= $time_online_ago;
    }

    public function attachNetwork($network, $identity): void
    {
        $networks = $this->userNetworks;
        foreach ($networks as $current) {
            if ($current->isFor($network, $identity)) {
                throw new \DomainException(Yii::t('auth','Network is already attached.'));
            }
        }
        $networks[] = UserNetwork::create($network, $identity);
        $this->userNetworks = $networks;
    }

}