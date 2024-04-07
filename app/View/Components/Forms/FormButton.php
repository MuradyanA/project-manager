<?php

namespace App\View\Components\forms;

use App\Services\Forms\FormBuilder;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormButton extends Component
{

    public string $actionName = "";

    public string $buttonClass = "";

    public function __construct(protected FormBuilder $builder, public string $name)
    {
        $btn = $builder->getButton($name);
        $this->actionName = $btn->actionName;
        $this->buttonClass = $btn->buttonClass;
    }



    public function render(): View|Closure|string
    {
        return view('components.forms.form-button');
    }
}
