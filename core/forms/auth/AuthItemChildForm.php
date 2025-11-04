<?php

namespace core\forms\auth;

use Yii;
use core\entities\Auth\AuthItem;
use core\entities\Auth\AuthItemChild;
use core\entities\Auth\AuthItemChildQuery;
use yii\helpers\ArrayHelper;

class AuthItemChildForm extends Yii\base\Model
{
    public $parent;
    public $child;

    /**
     * @var AuthItemChild
     */
    private $_authItemChild;

    public function __construct(AuthItemChild $authItemChild = null, $config = [])
    {
        if($authItemChild){
            $this->parent = $authItemChild->parent;
            $this->child = $authItemChild->child;
        }

        $this->_authItemChild = $authItemChild;

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['parent', 'child'], 'required'],
            [['parent', 'child'], 'string', 'max' => 64],
            [['parent', 'child'], 'unique', 'targetAttribute' => ['parent', 'child']],
            [['parent'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::className(), 'targetAttribute' => ['parent' => 'name']],
            [['child'], 'exist', 'skipOnError' => true, 'targetClass' => AuthItem::className(), 'targetAttribute' => ['child' => 'name']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'parent' => Yii::t('app', 'Parent'),
            'child' => Yii::t('app', 'Child'),
        ];
    }

    public static function getDb(): Yii\db\Connection
    {
        return Yii::$app->getDb();
    }

    public static function find(): AuthItemChildQuery
    {
        return new AuthItemChildQuery(get_called_class());
    }

    public static function tableName(): string
    {
        return 'auth_item_child';
    }

    public function dataModel(): ?AuthItemChild
    {
        return $this->_authItemChild;
    }

    public function rolesList(): array
    {
        return ArrayHelper::map(\Yii::$app->authManager->getRoles(), 'name', 'description');
    }
}
