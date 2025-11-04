<?php

namespace core\forms\auth;

use Yii;
use core\entities\Auth\AuthItem;
use core\entities\Auth\AuthItemQuery;
use core\entities\Auth\AuthRule;

class AuthItemForm extends Yii\base\Model
{
    public $name;
    public $type;
    public $description;
    public $rule_name;
    public $data;
    public $created_at;
    public $updated_at;

    /**
     * @var AuthItem
     */
    private $_authItem;

    public function __construct(AuthItem $authItem = null, $config = [])
    {
        if($authItem){
            $this->name = $authItem->name;
            $this->type = $authItem->type;
            $this->description = $authItem->description;
            $this->rule_name = $authItem->rule_name;
            $this->data = $authItem->data;
            $this->created_at = $authItem->created_at;
            $this->updated_at = $authItem->updated_at;
        }

        $this->_authItem = $authItem;

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name', 'type'], 'required'],
            [['type', 'created_at', 'updated_at'], 'integer'],
            [['description', 'data'], 'string'],
            [['name', 'rule_name'], 'string', 'max' => 64],
           // [['name'], 'unique'],
            [['rule_name'], 'exist', 'skipOnError' => true, 'targetClass' => AuthRule::className(), 'targetAttribute' => ['rule_name' => 'name']],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => Yii::t('app', 'Name'),
            'type' => Yii::t('app', 'Type'),
            'description' => Yii::t('app', 'Description'),
            'rule_name' => Yii::t('app', 'Rule Name'),
            'data' => Yii::t('app', 'Data'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public static function getDb(): Yii\db\Connection
    {
        return Yii::$app->getDb();
    }

    public static function find(): AuthItemQuery
    {
        return new AuthItemQuery(get_called_class());
    }

    public static function tableName(): string
    {
        return 'auth_item';
    }

    public function dataModel(): ?AuthItem
    {
        return $this->_authItem;
    }
}
