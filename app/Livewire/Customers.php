<?php

namespace App\Livewire;

use App\Livewire\Forms\CustomerForm;
use Livewire\Component;
use App\Models\Customer;
use App\Services\CustomerService;
use Livewire\Attributes\Rule;
use Livewire\Attributes\On;
use App\Services\Exceptions\ServiceException;
use App\Enums\NotificationType;


class Customers extends Component
{
    // #[Rule('required|string')]
    // public $fullName = "";

    // #[Rule('required|email')]
    // public $email = "";

    // #[Rule('required|numeric|min:11')]
    // public $phoneNumber = "";

    public $tableKey;

    public CustomerForm $customerForm;

    public function mount()
    {
        $this->tableKey = rand();
    }

    #[On('Edit')]
    public function edit($items)
    {
        $this->customerForm->setCustomerForm(Customer::findOrFail($items[0]));
    }

    #[On('Delete')]
    public function deleteCustomer($items)
    {
        try {
            (new CustomerService())->loadFieldValues(['id' => $items[0]])->delete();
            $this->tableKey = rand();
        } catch (ServiceException $e) {
            // $this->addError('ValidationError', $e->getMessage());
            $this->dispatch(
                'displayNotification',
                message: $e->getMessage(),
                type: NotificationType::Alert
            )->to(\App\Livewire\Notification::class);
        }
    }

    public function saveCustomer()
    {
        $this->customerForm->validate();
        $customer = $this->customerForm->all();
        $service = (new CustomerService())->loadFieldValues($customer);
        try {
            if ($customer['id']) {
                $service->update();
                $this->customerForm->reset();
            } else {
                $service->create();
                $this->customerForm->reset();
            }
            $this->tableKey = rand();
        } catch (ServiceException $e) {
            $this->addError('ValidationError', $e->getMessage());
        }
    }




    public function render()
    {
        return view('livewire.customers');
    }
}