<?php

namespace App\Services;

use App\Models\Sprint;
use App\Models\Task;

class SprintService extends BaseService
{

    public const VALIDATION_RULES = [
        'projectId' => 'required|int',
        'start' => 'required|date|before:end',
        'end' => 'required|date',
    ];

    protected const ENTITY_MODEL_CLASS = Sprint::class;

    protected function beforeAction($actionName)
    {
        if ($actionName == 'create') {
            $sprintNumber = Sprint::where('projectId', $this->fields['projectId'])->max('sprint') ?? 0;
            $this->fields['sprint'] = ++$sprintNumber;
        }
    }

    protected function getAdditionalValidationCallbacks($actionName): array
    {
        switch ($actionName) {
            case 'create':
                //check if the task(s) are in any sprint, if they are then throw an exception
            case 'update':
                return [
                    function ($validator) {
                        if (Sprint::whereBetween('start', [$this->fields['start'], $this->fields['end']])->orWhereBetween('end', [$this->fields['start'], $this->fields['end']])->exists()) {
                            $validator->errors()->add('additionalValidationError', "Sprint's start or end dates overlap an existing sprint(s).");
                        }
                    }
                ];
            case 'delete':
                return [
                    function ($validator) {
                        if (Task::where([
                            ['status', '!=', 'Proposed'],
                            ['sprintId', '=', $this->fields['id']]
                        ])->exists()){
                            $validator->errors()->add('additionalValidationError', "Sprint can't be deleted whether it doesn't have a task, or all the tasks have proposed state");
                        }
                        $sprint =  Sprint::findOrFail($this->fields['id']);
                        if(Sprint::where('projectId', $sprint->projectId)->max('sprint') != $sprint->sprint){
                            $validator->errors()->add('additionalValidationError', "Only last sprint can be deleted");
                        }   
                    }
                ];
            default:
                return [];
        }
    }
}
