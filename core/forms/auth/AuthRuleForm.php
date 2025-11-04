<?php

namespace core\forms\auth;

use Yii;
use core\entities\Auth\AuthRule;
use core\entities\Auth\AuthRuleQuery;

class AuthRuleForm extends Yii\base\Model
{
    public $name;
    public $data;
    public $created_at;
    public $updated_at;

    /**
     * @var AuthRule
     */
    private $_authRule;

    public function __construct(AuthRule $authRule = null, $config = [])
    {
        if($authRule){
            $this->name = $authRule->name;
            $this->data = $authRule->data;
            $this->created_at = $authRule->created_at;
            $this->updated_at = $authRule->updated_at;
        }

        $this->_authRule = $authRule;

        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['data'], 'string'],
            [['created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 64],
            [['name'], 'unique'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name' => Yii::t('app', 'Name'),
            'data' => Yii::t('app', 'Data'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public static function getDb(): Yii\db\Connection
    {
        return Yii::$app->getDb();
    }

    public static function find(): AuthRuleQuery
    {
        return new AuthRuleQuery(get_called_class());
    }

    public static function tableName(): string
    {
        return 'auth_rule';
    }

    public function dataModel(): ?AuthRule
    {
        return $this->_authRule;
    }
}
