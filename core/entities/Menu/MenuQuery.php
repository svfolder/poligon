<?php

namespace core\entities\Menu;

use Yii;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[Menu]].
 *
 * @see Menu
 */
class MenuQuery extends ActiveQuery
{
    /**
     * @param null $alias
     * @return $this
     */
    public function active($alias = null): MenuQuery
    {
        return $this->andWhere([($alias ? $alias . '.' : '') . 'status' => 1]);
    }

    /**
     * {@inheritdoc}
     * @return Menu[]|array
     */
    public function all($db = null): array
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Menu|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
