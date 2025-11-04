<?php

namespace core\entities\Auth;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[AuthItem]].
 *
 * @see AuthItem
 */
class AuthItemQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return AuthItem[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return AuthItem|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
