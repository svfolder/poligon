<?php


namespace core\traits;


use core\entities\Kit\Project;
use core\services\kit\ProjectService;
use Yii;

trait ProjectTrait
{

    public function currentProject(): ?Project
    {
        /** @var ProjectService $projectService */
        if ($projectService = Yii::$app->project) {
            return $projectService->getCurrent();
        }
        return null;
    }

    public function isDisable(): bool
    {
        if ($project = $this->currentProject()){
            if ($project->id !== 1){
                return true;
            }
        }
        return false;
    }

    public function iffOverrideProject()
    {
        if ($this->isDisable()){
            $project = $this->currentProject();
            $this->project_id = $project->id;
        }
    }

}