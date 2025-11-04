<?php

namespace core\entities\Menu;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[MenuItem]].
 *
 * @see MenuItem
 */
class MenuItemQuery extends ActiveQuery
{
    /**
     * @param null $alias
     * @return $this
     */
    public function active($alias = null): MenuItemQuery
    {
        return $this->andWhere([($alias ? $alias . '.' : '') . 'status' => 1]);
    }

    /**
     * {@inheritdoc}
     * @return MenuItem[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return MenuItem|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
