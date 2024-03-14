<?php

namespace App\Livewire\Forms;

use App\Models\Customer;
use App\Services\CustomerService;
use Livewire\Attributes\Validate;
use Livewire\Form;
use Livewire\Attributes\Locked;

class CustomerForm extends Form
{
    #[Locked]
    public $id;

    #[Validate]
    public $fullName;

    #[Validate]
    public $email;

    #[Validate]
    public $phoneNumber;

    public function rules()
    {

        return $this->id == null ?
            CustomerService::getValidationRules('create')
            :
            CustomerService::getValidationRules('update');
    }

    public function setCustomerForm(Customer $customer)
    {
        $this->id = $customer->id;
        $this->fullName = $customer->fullName;
        $this->email = $customer->email;
        $this->phoneNumber = $customer->phoneNumber;
    }
}
