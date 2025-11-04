<?php

namespace core\forms\Menu;

use Yii;
use core\entities\Menu\Menu;
use core\entities\Menu\MenuItem;
use core\entities\Menu\MenuItemQuery;

class MenuItemForm extends Yii\base\Model
{
    public $parent_id;
    public $menu_id;
    public $name;
    public $link;
    public $sort;
    public $status;

    /**
     * @var MenuItem
     */
    protected $_menuItem;

    public function __construct(MenuItem $menuItem = null, $config = [])
    {
        if ($menuItem) {
            $this->parent_id = $menuItem->parent_id;
            $this->menu_id = $menuItem->menu_id;
            $this->name = $menuItem->name;
            $this->link = $menuItem->link;
            $this->sort = $menuItem->sort;
            $this->status = $menuItem->status;
        } else {

        }

        $this->_menuItem = $menuItem;

        parent::__construct($config);
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

    public static function getDb(): Yii\db\Connection
    {
        return Yii::$app->getDb();
    }

    public static function find(): MenuItemQuery
    {
        return new MenuItemQuery(get_called_class());
    }

    public static function tableName(): string
    {
        return 'menu_item';
    }

    public function dataModel(): ?MenuItem
    {
        return $this->_menuItem;
    }
}
