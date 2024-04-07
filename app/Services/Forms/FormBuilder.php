<?php

namespace App\Services\Forms;

use App\Services\Exceptions\FormBuilderException;

use function PHPUnit\Framework\isNull;

class FormBuilder
{

    public string $formTitle = "";

    protected array $body = [];

    protected array $sections = [];

    protected array $buttons = [];


    public function __construct(public string $onSubmitAction, public string $positionType = "static")
    {
    }

    public function addTitle(string $title)
    {
        $this->formTitle = $title;
        return $this;
    }

    public function getFormTitle()
    {
        return $this->formTitle;
    }

    public function addFormSection(string $sectionName)
    {
        $this->body[$sectionName] = [];
        return $this;
    }

    public function addButton(FormButton $button){
        $this->buttons[] = $button;
        return $this;
    }

    public function getButton(string $buttonName){
        return array_filter($this->buttons, fn($value)=>$buttonName == $value->name)[0];
    }

    public function addInput(FormInputInterface $input, ?string $sectionName = null)
    {
        if ($sectionName == null) {
            if ($this->isSectionExists()) throw FormBuilderException::FormBuilderError('Inputs must be added into sections whlie sections are available.');
            $this->body[] = $input;
        } else {
            $this->sections[] = $input;
        }
        return $this;
    }

    public function getButtons(){
        return $this->buttons;
    }

    public function isSectionExists(): bool
    {
        return count($this->sections) ? true : false;
    }

    public function getSections()
    {
        return $this->sections;
    }

    public function getInputs(?string $sectionName = null)
    {
        if (is_null($sectionName)) {
            return $this->body;
        } else {
            return $this->sections[$sectionName];
        }
    }

    public function getInput(string $inputName)
    {
        foreach ($this->body as $input) {
            if ($input->name == $inputName)
                return $input;
        }
        foreach ($this->sections as $name => $arr) {
            foreach ($this->sections['name'] as $input) {
                if ($input->name == $inputName)
                    return $input;
            }
        }
        throw FormBuilderException::FormBuilderError("The given name wasn't found");
    }
}
