<?php

namespace core\entities\Menu;

use Yii;
use core\traits\ModelTrait;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "menu".
 *
 * @property int $id
 * @property string $name
 * @property string $alias
 * @property int $status
 *
 * @property MenuItem[] $menuItems
 */
class Menu extends ActiveRecord
{
    use ModelTrait;

    public static function tableName(): string
    {
        return 'menu';
    }

    public static function create($name, $alias, $status): self
    {
        $menu = new static();
        $menu->name = $name;
        $menu->alias = $alias;
        $menu->status = $status;
        return $menu;
    }

    public function edit($name, $alias, $status): void
    {
        $this->name = $name;
        $this->alias = $alias;
        $this->status = $status;
    }

    public function rules(): array
    {
        return [
            [['name', 'alias'], 'required'],
            [['status'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['alias'], 'string', 'max' => 50],
            [['alias'], 'unique'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'alias' => Yii::t('app', 'Alias'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    public function getMenuItems()
    {
        return $this->hasMany(MenuItem::className(), ['menu_id' => 'id']);
    }

    public static function find(): MenuQuery
    {
        return new MenuQuery(get_called_class());
    }
}
