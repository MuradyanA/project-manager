<?php

namespace App\Services\Forms;

class FormTextInput implements FormInputInterface{

    protected string $viewName = "forms.text-input";

    public function __construct(
        public string $name, public string $caption, public string $type="text", 
        public bool $show = true, public bool $readOnly = false,
        public bool $disabled = false) {    
    }

    public function getViewName(){
        return $this->viewName;
    }
}