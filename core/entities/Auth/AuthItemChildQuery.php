<?php

namespace core\entities\Auth;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[AuthItemChild]].
 *
 * @see AuthItemChild
 */
class AuthItemChildQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return AuthItemChild[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return AuthItemChild|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
