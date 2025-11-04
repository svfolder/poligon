<?php

namespace core\behaviors;

use core\entities\Property;
use yii\base\Behavior;
use yii\base\Event;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class PropertyBehavior extends Behavior
{
    public $attribute = 'prop';
    public $jsonAttribute = 'property_json';

    public function events(): array
    {
        return [
            ActiveRecord::EVENT_AFTER_FIND => 'onAfterFind',
            ActiveRecord::EVENT_BEFORE_INSERT => 'onBeforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'onBeforeSave',
        ];
    }

    public function onAfterFind(Event $event): void
    {
        $model = $event->sender;
        $property = Json::decode($model->getAttribute($this->jsonAttribute));

        $model->{$this->attribute} = new Property(
            ArrayHelper::getValue($property, 'genBatch'),
            ArrayHelper::getValue($property, 'genTruncate'),
            ArrayHelper::getValue($property, 'genImport'),
            ArrayHelper::getValue($property, 'genReadRepository'),
            ArrayHelper::getValue($property, 'addTransactionService'),
            ArrayHelper::getValue($property, 'genExtendService'),
            ArrayHelper::getValue($property, 'genExport'),
            ArrayHelper::getValue($property, 'genCreateAjax'),
            ArrayHelper::getValue($property, 'shortMode'),
            ArrayHelper::getValue($property, 'genRememberFilter'),
        );
    }

    public function onBeforeSave(Event $event): void
    {
        $model = $event->sender;

        $model->setAttribute('property_json', Json::encode([
            'genBatch' => $model->{$this->attribute}->genBatch,
            'genTruncate' => $model->{$this->attribute}->genTruncate,
            'genImport' => $model->{$this->attribute}->genImport,
            'genReadRepository' => $model->{$this->attribute}->genReadRepository,
            'addTransactionService' => $model->{$this->attribute}->addTransactionService,
            'genExtendService' => $model->{$this->attribute}->genExtendService,
            'genExport' => $model->{$this->attribute}->genExport,
            'genCreateAjax' => $model->{$this->attribute}->genCreateAjax,
            'shortMode' => $model->{$this->attribute}->shortMode,
            'genRememberFilter' => $model->{$this->attribute}->genRememberFilter,
        ]));
    }
}
