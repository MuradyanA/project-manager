<?php

namespace App\Services\ViewDetails;

class ButtonCreator
{
    // <x-forms.details-view-button caption='caption' actionName='save' confirmationMessage="Hello" />
    public function __construct(protected string $caption, protected string $actionName, protected string $confirmationMessage = "", protected string $svg = "", protected string $class = "") {
    }

    public function getData(){
        return ['caption' => $this->caption, 'actionName' => $this->actionName, 'confirmationMessage' => $this->confirmationMessage, 'svg' => $this->svg, 'class' => $this->class];
    }

}