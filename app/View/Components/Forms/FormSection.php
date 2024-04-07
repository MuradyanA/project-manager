<?php

namespace App\View\Components\Forms;

use App\Services\Forms\FormBuilder;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FormSection extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public FormBuilder $builder, public string $name)
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.form-section');
    }
}
