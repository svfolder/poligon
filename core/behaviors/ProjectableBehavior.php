<?php


namespace core\behaviors;

use Yii;
use core\services\kit\ProjectService;
use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;

class ProjectableBehavior extends AttributeBehavior
{
    public $attribute = 'project_id';
    public $value;

    public function init()
    {
        parent::init();

        if (empty($this->attributes)) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_INSERT => $this->attribute,
                BaseActiveRecord::EVENT_BEFORE_UPDATE => $this->attribute,
            ];
        }
    }

    protected function getValue($event)
    {
        if ($this->value === null) {
            /** @var ProjectService $project */
            if ($project = Yii::$app->project){
                return $project->getCurrent()->id;
            }
        }

        return parent::getValue($event);
    }
}