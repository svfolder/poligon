<?php

namespace core\entities\Auth;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "auth_item_child".
 *
 * @property string $parent
 * @property string $child
 *
 * @property AuthItem $parent0
 * @property AuthItem $child0
 */
class AuthItemChild extends ActiveRecord
{
    public static function tableName(): string
    {
        return 'auth_item_child';
    }

    public static function create($parent, $child): self
    {
        $authItemChild = new static();
        $authItemChild->parent = $parent;
        $authItemChild->child = $child;
        return $authItemChild;
    }

    public function edit($parent, $child): void
    {
        $this->parent = $parent;
        $this->child = $child;
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

    /**
     * Gets query for [[Parent0]].
     *
     * @return \yii\db\ActiveQuery|AuthItemQuery
     */
    public function getParent0()
    {
        return $this->hasOne(AuthItem::className(), ['name' => 'parent']);
    }

    /**
     * Gets query for [[Child0]].
     *
     * @return \yii\db\ActiveQuery|AuthItemQuery
     */
    public function getChild0()
    {
        return $this->hasOne(AuthItem::className(), ['name' => 'child']);
    }

    public static function find(): AuthItemChildQuery
    {
        return new AuthItemChildQuery(get_called_class());
    }
}
