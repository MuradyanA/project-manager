<?php

namespace App\Services;

use App\Models\Sprint;

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
        $sprintNumber = Sprint::where('projectId', $this->fields['projectId'])->max('sprint') ?? 0;
        $this->fields['sprint'] = ++$sprintNumber;
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
            default:
                return [];
        }
    }

}