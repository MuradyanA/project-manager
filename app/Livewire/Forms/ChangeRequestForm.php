<?php

namespace App\Livewire\Forms;

use App\Services\RequestService;
use Livewire\Attributes\Validate;
use Livewire\Form;

class ChangeRequestForm extends Form
{
    #[Validate]
    public int $projectId = 0;
    #[Validate]
    public string $title = "";
    #[Validate]
    public string $request = "";

    public function rules()
    {
        return RequestService::getValidationRules('create');
    }
}
