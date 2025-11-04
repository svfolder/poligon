<?php

namespace core\forms\Language;

use Yii;
use core\entities\Language\Language;
use core\entities\Language\LanguageQuery;

class LanguageForm extends Yii\base\Model
{
    public $name;
    public $code;
    public $status;

    /**
     * @var Language
     */
    private $_language;

    public function __construct(Language $language = null, $config = [])
    {
        if ($language) {
            $this->name = $language->name;
            $this->code = $language->code;
            $this->status = $language->status;
        } else {

        }

        $this->_language = $language;

        parent::__construct($config);
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

    public static function getDb(): Yii\db\Connection
    {
        return Yii::$app->getDb();
    }

    public static function find(): LanguageQuery
    {
        return new LanguageQuery(get_called_class());
    }

    public static function tableName(): string
    {
        return 'language';
    }

    public function dataModel(): ?Language
    {
        return $this->_language;
    }
}
