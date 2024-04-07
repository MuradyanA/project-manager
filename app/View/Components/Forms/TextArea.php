<?php

namespace App\View\Components\forms;

use App\Services\Forms\FormBuilder;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class TextArea extends Component
{

    public string $type = "";

    public string $caption = "";

    public bool $readOnly = false;

    public string $class = "";

    public bool $disabled = false;

    public bool $show = true;

    public function __construct(public string $name, protected FormBuilder $builder)
    {
        $input = $builder->getInput($name);
        $this->type = $input->type;
        $this->caption = $input->caption;
        $this->readOnly = $input->readOnly;
        $this->disabled = $input->disabled;
        $this->show = $input->show;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.forms.text-area');
    }
}
