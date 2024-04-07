<?php

namespace App\Services;

use App\Models\Sprint;
use App\Models\Task;
use App\Models\Request;
use App\Services\Exceptions\ServiceException;
use Carbon\Carbon;
use App\Services\SprintService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Arr;

class TaskService extends BaseService
{

    protected const VALIDATION_RULES = [
        'projectId' => ['required', 'exists:projects,id'],
        'requestId' => ['unique:tasks,requestId'],
        'sprintId' => ['required', 'exists:sprints,id'],
        'title' => ['string'],
        'body' => ['nullable', 'string'],
        'start' => ['required', 'date', 'before:end'],
        'end' => ['required', 'date'],
    ];

    protected const ENTITY_MODEL_CLASS = Task::class;

    #Override
    public static function getValidationRules($actionName)
    {
        $validationRules = parent::getValidationRules($actionName);
        if ($actionName == 'update') {
            Arr::except($validationRules, ['sprintId']);
        }
        return $validationRules;
    }

    public function moveTaskStageForwardOrBack(int $id, bool $moveForward = true)
    {
        $task = self::ENTITY_MODEL_CLASS::findOrFail($id);
        if ($moveForward) {
            if ($task['status'] == 'Closed') return $task;
            $task['status'] = match ($task['status']) {
                'Proposed' => 'Active',
                'Active' => 'Resolved',
                'Resolved' => 'Closed',
            };
        } else {
            if ($task['status'] == 'Proposed') return $task;
            $task['status'] = match ($task['status']) {
                'Active' => 'Proposed',
                'Resolved' => 'Active',
                'Closed' => 'Resolved'
            };
        }
        $task->save();
        return $task;
    }


    public function toggleTaskActiveStatus($id)
    {
        $task = self::ENTITY_MODEL_CLASS::findOrFail($id);
        if ($task['status'] == 'Rejected') {
            $task['status'] = 'Proposed';
        } else {
            $task['status'] = 'Rejected';
        }
        $task->save();
        return $task;
    }

    // public function createTasksForSprint($requestIds, $sprint)
    // {
    //     foreach ($requestIds as $requestId) {
    //         $request = Request::findOrFail($requestId);
    //         # code...
    //         $this->fields = [
    //             'projectId' => $request->projectId,
    //             'sprintId' => $sprint->id,
    //             'requestId' => $requestId,
    //             'title' => $request->title,
    //             'body' => $request->request,
    //             'start' => $sprint->start,
    //             'end' => $sprint->end
    //         ];
    //         $tasks[] = $this->create();
    //     }
    //     return $tasks;
    // }

    // public function createTasksAndSprintFromRequest(int $projectId, array $requestIds, Carbon $sprintStart, Carbon $sprintEnd): array
    // {
    //     $tasks = [];
    //     DB::transaction(function () use ($projectId, $requestIds, $sprintStart, $sprintEnd, &$tasks) {
    //         // dd($sprintStart, $sprintEnd);
    //         $sprint = (new SprintService())->loadFieldValues([
    //             'projectId' => $projectId,
    //             'start' => $sprintStart,
    //             'end' => $sprintEnd
    //         ])->create();
    //         $tasks = $this->createTasksForSprint($requestIds, $sprint);
    //     });
    //     return $tasks;
    // }

    protected function getAdditionalValidationCallbacks($actionName): ?array
    {
        switch ($actionName) {
            case 'create':
                $sprint = Sprint::find($this->fields['sprintId']);
                $task = $this->fields;
            case 'update':
                if (!isset($task)) {
                    $task = Task::find($this->fields['id']);
                }
                if (!isset($sprint)) {
                    $task = Sprint::find($task['sprintId']);
                }
                $task = $actionName == 'update' ?
                    array_merge($task->toArray(), $this->fields) : $this->fields;
                return [
                    function ($validator) use ($sprint, $task) {
                        if (!(Carbon::parse($task['start'])->between($sprint['start'], $sprint['end']) && Carbon::parse($task['end'])->between($sprint['start'], $sprint['end']))) {
                            $validator->errors()->add('additionalValidationError', "The start and end of the task must be within the sprint period.");
                        }
                    }
                ];
                break;
            case 'delete':
                $task = Task::findOrFail( $this->fields['id']);
                return [
                    function ($validator) use ($task) {
                        if ($task->status !='Proposed') {
                            $validator->errors()->add('additionalValidationError', "Only proposed tasks can be deleted.");
                        }
                    }
                ];
        }
    }
}
