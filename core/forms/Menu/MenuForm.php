<?php

namespace core\forms\Menu;

use Yii;
use core\entities\Menu\Menu;
use core\entities\Menu\MenuQuery;

class MenuForm extends Yii\base\Model
{
    public $name;
    public $alias;
    public $status;

    /**
     * @var Menu
     */
    protected $_menu;

    public function __construct(Menu $menu = null, $config = [])
    {
        if ($menu) {
            $this->name = $menu->name;
            $this->alias = $menu->alias;
            $this->status = $menu->status;
        } else {

        }

        $this->_menu = $menu;

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name', 'alias'], 'required'],
            [['status'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['alias'], 'string', 'max' => 50],
            [ ['alias'], 'unique', 'filter' => $this->_menu ? ['<>', 'id', $this->_menu->id] : null, ],
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

    public static function getDb(): Yii\db\Connection
    {
        return Yii::$app->getDb();
    }

    public static function find(): MenuQuery
    {
        return new MenuQuery(get_called_class());
    }

    public static function tableName(): string
    {
        return 'menu';
    }

    public function dataModel(): ?Menu
    {
        return $this->_menu;
    }
}
