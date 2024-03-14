<?php

namespace App\Livewire\Forms;

use App\Services\CommentService;
use Livewire\Form;
use Livewire\Attributes\Validate;

class CommentForm extends Form
{
    #[Validate]
    public string $comment = '';

    public function rules()
    {
        return array_filter(CommentService::getValidationRules('create'), fn($value, $key) => $key == 'comment', ARRAY_FILTER_USE_BOTH);
    }

}
