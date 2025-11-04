<?php

namespace core\entities\Language;

use Yii;
use core\traits\ModelTrait;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "language".
 *
 * @property int $id
 * @property string $name
 * @property string $code
 * @property int $status
 */
class Language extends ActiveRecord
{
    use ModelTrait;

    public static function tableName(): string
    {
        return 'language';
    }

    public static function create($name, $code, $status): self
    {
        $language = new static();
        $language->name = $name;
        $language->code = $code;
        $language->status = $status;
        return $language;
    }

    public function edit($name, $code, $status): void
    {
        $this->name = $name;
        $this->code = $code;
        $this->status = $status;
    }

    public function rules(): array
    {
        return [
            [['name', 'code'], 'required'],
            [['status'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 10],
            [['code'], 'unique'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'code' => Yii::t('app', 'Language Code'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    public static function find(): LanguageQuery
    {
        return new LanguageQuery(get_called_class());
    }
}
