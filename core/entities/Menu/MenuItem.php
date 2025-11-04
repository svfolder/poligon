<?php

namespace core\entities\Menu;

use Yii;
use core\traits\ModelTrait;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "menu_item".
 *
 * @property int $id
 * @property int|null $parent_id
 * @property int $menu_id
 * @property string $name
 * @property string $link
 * @property int $sort
 * @property int $status
 *
 * @property Menu $menu
 * @property MenuItem $parent
 * @property MenuItem[] $menuItems
 */
class MenuItem extends ActiveRecord
{
    use ModelTrait;

    public function behaviors(): array
    {
        return [
            'sort' => [
                'class' => 'himiklab\sortablegrid\SortableGridBehavior',
                'sortableAttribute' => 'sort',
            ],
        ];
    }

    public static function tableName(): string
    {
        return 'menu_item';
    }

    public static function create($parent_id, $menu_id, $name, $link, $sort, $status): self
    {
        $parent_id = $parent_id ? $parent_id : null;

        $menuItem = new static();
        $menuItem->parent_id = $parent_id;
        $menuItem->menu_id = $menu_id;
        $menuItem->name = $name;
        $menuItem->link = $link;
        $menuItem->sort = $sort;
        $menuItem->status = $status;
        return $menuItem;
    }

    public function edit($parent_id, $menu_id, $name, $link, $sort, $status): void
    {
        $parent_id = $parent_id ? $parent_id : null;

        $this->parent_id = $parent_id;
        $this->menu_id = $menu_id;
        $this->name = $name;
        $this->link = $link;
        $this->sort = $sort;
        $this->status = $status;
    }

    public function rules(): array
    {
        return [
            [['parent_id', 'menu_id', 'sort', 'status'], 'integer'],
            [['menu_id', 'name', 'link'], 'required'],
            [['name', 'link'], 'string', 'max' => 255],
            [['menu_id'], 'exist', 'skipOnError' => true, 'targetClass' => Menu::className(), 'targetAttribute' => ['menu_id' => 'id']],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => MenuItem::className(), 'targetAttribute' => ['parent_id' => 'id']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'menu_id' => Yii::t('app', 'Menu ID'),
            'name' => Yii::t('app', 'Name'),
            'link' => Yii::t('app', 'Link'),
            'sort' => Yii::t('app', 'Sort'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    public function getMenu()
    {
        return $this->hasOne(Menu::className(), ['id' => 'menu_id']);
    }

    public function getParent()
    {
        return $this->hasOne(MenuItem::className(), ['id' => 'parent_id']);
    }

    public function getMenuItems()
    {
        return $this->hasMany(MenuItem::className(), ['parent_id' => 'id']);
    }

    public static function find(): MenuItemQuery
    {
        return new MenuItemQuery(get_called_class());
    }
}
