<?php

namespace App\Livewire\Traits;

use App\Services\Exceptions\ServiceException;

trait WithServiceMethods
{
    public function save()
    {
        try {
            $this->form->validate();
            (new $this->serviceClass())->loadFieldValues($this->form->all())->create();
            $this->form->reset();
        } catch (ServiceException $e) {
            $this->addError('ValidationError', $e->getMessage());
        }
    }
}
