<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use App\Models\Task;
use App\Models\TaskUser;
use Carbon\Carbon;
use App\Services\Exceptions\ServiceException;


class TaskUserAssociationService
{

    public function createAssociation(int $user_id, int $task_id)
    {
        $this->validate(['user_id' => $user_id, 'task_id' => $task_id], 'create');
        TaskUser::create([
            'user_id' => $user_id,
            'task_id' => $task_id,
        ]);
    }

    public function deleteAssociation(int $user_id, int $task_id)
    {
        $this->validate(['user_id' => $user_id, 'task_id' => $task_id], 'delete');
        TaskUser::where(['user_id' => $user_id, 'task_id' => $task_id])->delete();
    }

    protected function validate($fields, $action)
    {
        // dd($fields);
        $rules = [
            'user_id' => 'required|exists:users,id',
            'task_id' => 'required|exists:tasks,id',
        ];
        $validator = Validator::make($fields, $rules);
        if ($action == 'create') {
            $task = Task::find($fields['task_id']);
            $validator->after([
                function ($validator) use ($fields, $task) {
                    if (in_array($task->status, ['Resolved', 'Rejected', 'Closed']))
                        $validator->errors()->add('additionalValidationError', 'The task status must not be Rejected, Resolved or Closed');
                },
                function ($validator) use ($fields, $task) {
                    if ($task->load('sprint')->end < Carbon::today())
                        $validator->errors()->add('additionalValidationError', 'The sprint associated with the task is ended');
                },
                function ($validator) use ($fields, $task) {
                    if (TaskUser::where(['task_id' => $task->id, 'user_id' => $fields['user_id']])->exists())
                        $validator->errors()->add('additionalValidationError', 'This association already exists');
                }
            ]);
        }
        if ($validator->fails()) {
            $errors = $validator->errors()->all();
            throw ServiceException::ValidationError(is_array($errors) ? implode(' ', $errors) : $errors);
        }
    }
}
