<?php

namespace core\forms;

use Yii;
use core\entities\Property;
use yii\base\Model;

class PropertyForm extends Model
{
    public $genBatch;
    public $genTruncate;
    public $genImport;
    public $genReadRepository;
    public $addTransactionService;
    public $genExtendService;
    public $genExport;
    public $genCreateAjax;
    public $shortMode;
    public $genRememberFilter;

    public function __construct(Property $property = null, $config = [])
    {
        if ($property) {
            $this->genBatch = $property->genBatch;
            $this->genTruncate = $property->genTruncate;
            $this->genImport = $property->genImport;
            $this->genReadRepository = $property->genReadRepository;
            $this->addTransactionService = $property->addTransactionService;
            $this->genExtendService = $property->genExtendService;
            $this->genExport = $property->genExport;
            $this->genCreateAjax = $property->genCreateAjax;
            $this->shortMode = $property->shortMode;
            $this->genRememberFilter = $property->genRememberFilter;
        }
        parent::__construct($config);
    }

    public function rules(): array
    {
        return [
            [['genBatch'], 'integer'],
            [['genTruncate'], 'integer'],
            [['genImport'], 'integer'],
            [['genReadRepository'], 'integer'],
            [['addTransactionService'], 'integer'],
            [['genExtendService'], 'integer'],
            [['genExport'], 'integer'],
            [['genCreateAjax'], 'integer'],
            [['shortMode'], 'integer'],
            [['genRememberFilter'], 'integer'],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'genBatch' => Yii::t('app', 'Generate Batch'),
            'genTruncate' => Yii::t('app', 'Generate Truncate'),
            'genImport' => Yii::t('app', 'Generate Import'),
            'genReadRepository' => Yii::t('app', 'Generate Read Repository'),
            'addTransactionService' => Yii::t('app', 'Add Transaction Service'),
            'genExtendService' => Yii::t('app', 'Generate Extend Service'),
            'genExport' => Yii::t('app', 'Generate Export'),
            'genCreateAjax' => Yii::t('app', 'Generate Create Ajax'),
            'shortMode' => Yii::t('app', 'Short Mode'),
            'genRememberFilter' => Yii::t('app', 'Generate Remember Filter'),
        ];
    }
}
