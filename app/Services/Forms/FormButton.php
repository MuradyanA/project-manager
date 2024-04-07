<?php

namespace App\Services\Forms;

use App\Services\Exceptions\FormBuilderException;

use function PHPUnit\Framework\isNull;

class FormButton
{
    protected string $viewName = "forms.form-button";

    public function __construct(public string $name, public string $actionName, public string $buttonClass = 'bg-red-600') {
    }

    public function getViewName(){
        return $this->viewName;
    }


}