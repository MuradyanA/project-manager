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
use App\Services\Forms\FormBuilder;
use App\Services\Forms\FormTextInput;
use App\Services\Filter;

class Customers extends Component
{
    public $tableKey;

    public Filter $filter;

    public CustomerForm $customerForm;

    public function mount()
    {
        $this->tableKey = rand();
        $this->filter = new Filter(['fullName', 'email', 'phoneNumber'], ['id'], dateTimeFields: ['created_at'], f : 'custF');
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

    public function createBuilder()
    {
        $builder = new FormBuilder('saveCustomer');
        $builder
            ->addTitle($this->customerForm->id ? 'Update Customer' : 'Create Customer ')
            ->addInput(
                new FormTextInput('customerForm.id', 'ID', 'text', (bool) $this->customerForm->id, true, true),
            )
            ->addInput(new FormTextInput('customerForm.fullName', 'Name', 'text'))
            ->addInput(new FormTextInput('customerForm.email', 'Email', 'text'))
            ->addInput(new FormTextInput('customerForm.phoneNumber', 'Phone Number', 'text'));
        return $builder;
    }


    public function render()
    {
        return view('livewire.customers');
    }
}
