<?php

namespace core\entities\Auth;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "auth_rule".
 *
 * @property string $name
 * @property resource|null $data
 * @property int|null $created_at
 * @property int|null $updated_at
 *
 * @property AuthItem[] $authItems
 */
class AuthRule extends ActiveRecord
{
    public function behaviors(): array
    {
        return [
            'timestamp' => [
                'class' => 'yii\behaviors\TimestampBehavior',
                'attributes' => [
                    yii\db\ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    yii\db\ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
        ];
    }

    public static function tableName(): string
    {
        return 'auth_rule';
    }

    public static function create($name, $data, $created_at, $updated_at): self
    {
        $authRule = new static();
        $authRule->name = $name;
        $authRule->data = $data;
        $authRule->created_at = $created_at;
        $authRule->updated_at = $updated_at;
        return $authRule;
    }

    public function edit($name, $data, $created_at, $updated_at): void
    {
        $this->name = $name;
        $this->data = $data;
        $this->created_at = $created_at;
        $this->updated_at = $updated_at;
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

    /**
     * Gets query for [[AuthItems]].
     *
     * @return \yii\db\ActiveQuery|AuthItemQuery
     */
    public function getAuthItems()
    {
        return $this->hasMany(AuthItem::className(), ['rule_name' => 'name']);
    }

    public static function find(): AuthRuleQuery
    {
        return new AuthRuleQuery(get_called_class());
    }
}
