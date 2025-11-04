<?php

namespace core\entities\Auth;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[AuthRule]].
 *
 * @see AuthRule
 */
class AuthRuleQuery extends ActiveQuery
{
    /**
     * {@inheritdoc}
     * @return AuthRule[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return AuthRule|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
