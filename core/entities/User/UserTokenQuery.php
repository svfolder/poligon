<?php

namespace core\entities\User;

/**
 * This is the ActiveQuery class for [[UserToken]].
 *
 * @see UserToken
 */
class UserTokenQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return UserToken[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return UserToken|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
