<?php

namespace App\Livewire\Forms;

use App\Services\SprintService;
use App\Services\TaskService;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Illuminate\Support\Arr;

class SprintForm extends Form
{
    #[Validate]
    public string $start = "";
    #[Validate]
    public string $end = "";

    public function rules()
    {
        return Arr::only(SprintService::getValidationRules('create'), ['start', 'end']);
    }
}
