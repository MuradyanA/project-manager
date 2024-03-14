<?php

namespace App\Livewire\Forms;

use App\Services\TaskService;
use Livewire\Attributes\Validate;
use Livewire\Form;

class SprintForm extends Form
{
    #[Validate]
    public int $projectId = 0;
    #[Validate]
    public int $sprintId = 0;
    #[Validate]
    public string $task = "";
    #[Validate]
    public string $start = "";
    #[Validate]
    public string $end = "";

    public function rules()
    {
        return TaskService::getValidationRules('create');
    }

}
