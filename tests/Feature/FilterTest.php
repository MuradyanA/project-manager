<?php

namespace Tests\Feature;

use App\Livewire\Customers;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Livewire\Livewire;
use App\Livewire\TableView;
use App\Services\TableViews\CustomersTable;
use App\Models\Customer;
use Carbon\Carbon;

class FilterTest extends TestCase
{
    use RefreshDatabase;
    public function test_that_new_filter_dialog_is_opened()
    {
        Livewire::test(TableView::class, ['tableClass' => CustomersTable::class])
            ->call('action', 'Filter')
            ->assertSee('Please select a field to filter');
    }

    public function test_filter_users_by_text_value()
    {
        $this->createCustomers();
        $customer = Customer::first();
        $component = Livewire::test(Customers::class);

        $filter = $component->filter;

        Livewire::test(TableView::class, ['tableClass' => CustomersTable::class, 'filter' => $filter])
            ->call('action', 'Filter')
            ->set('filter.currentField.name', 'fullName')
            ->assertSet('filter.currentField.name', 'fullName')
            ->call('callFilterMethod', 'getFieldData')
            ->assertSee('Previous')
            ->set('filter.conditionsFormFields.operator', 'Contains')
            ->set('filter.conditionsFormFields.value', 'Arm')
            ->call('callFilterMethod', 'addConditions')
            ->assertSee('Armen');

        Livewire::test(TableView::class, ['tableClass' => CustomersTable::class, 'filter' => $filter])
            ->call('action', 'Filter')
            ->set('filter.currentField.name', 'fullName')
            ->assertSet('filter.currentField.name', 'fullName')
            ->call('callFilterMethod', 'getFieldData')
            ->assertSee('Previous')
            ->set('filter.conditionsFormFields.operator', 'Starts With')
            ->set('filter.conditionsFormFields.value', 'Arm')
            ->call('callFilterMethod', 'addConditions')
            ->assertSee('Armen');

        Livewire::test(TableView::class, ['tableClass' => CustomersTable::class, 'filter' => $filter])
            ->call('action', 'Filter')
            ->set('filter.currentField.name', 'fullName')
            ->assertSet('filter.currentField.name', 'fullName')
            ->call('callFilterMethod', 'getFieldData')
            ->assertSee('Previous')
            ->set('filter.conditionsFormFields.operator', 'Ends With')
            ->set('filter.conditionsFormFields.value', 'men')
            ->call('callFilterMethod', 'addConditions')
            ->assertSee('Armen');

        Livewire::test(TableView::class, ['tableClass' => CustomersTable::class, 'filter' => $filter])
            ->call('action', 'Filter')
            ->set('filter.currentField.name', 'fullName')
            ->assertSet('filter.currentField.name', 'fullName')
            ->call('callFilterMethod', 'getFieldData')
            ->assertSee('Previous')
            ->set('filter.conditionsFormFields.operator', 'Exact Match')
            ->set('filter.conditionsFormFields.value', 'Armen')
            ->call('callFilterMethod', 'addConditions')
            ->assertSee('Armen');


        $otherCustomerName = substr($customer->fullName, 0, 5);
        Livewire::test(TableView::class, ['tableClass' => CustomersTable::class, 'filter' => $filter])
            ->call('action', 'Filter')
            ->set('filter.currentField.name', 'fullName')
            ->assertSet('filter.currentField.name', 'fullName')
            ->call('callFilterMethod', 'getFieldData')
            ->assertSee('Previous')
            ->set('filter.conditionsFormFields.operator', 'Contains')
            ->set('filter.conditionsFormFields.value', 'Armen')
            ->call('callFilterMethod', 'addConditions')
            ->set('filter.conditionsFormFields.andOr', 'or')
            ->set('filter.conditionsFormFields.operator', 'Contains')
            ->set('filter.conditionsFormFields.value', $otherCustomerName)
            ->call('callFilterMethod', 'addConditions')
            ->assertSee('Armen')
            ->assertSee($otherCustomerName);

        Livewire::test(TableView::class, ['tableClass' => CustomersTable::class, 'filter' => $filter])
            ->call('action', 'Filter')
            ->set('filter.currentField.name', 'email')
            ->assertSet('filter.currentField.name', 'email')
            ->call('callFilterMethod', 'getFieldData')
            ->assertSee('Previous')
            ->set('filter.conditionsFormFields.operator', 'Contains')
            ->set('filter.conditionsFormFields.value', 'arm')
            ->call('callFilterMethod', 'addConditions')
            ->assertSee('armen@mail.ru');
    }

    public function test_filter_users_by_integer_value()
    {
        $this->createCustomers();
        $customer = Customer::first();
        $myCustomer = Customer::where('fullName', 'Armen')->first();
        $component = Livewire::test(Customers::class);

        $filter = $component->filter;

        Livewire::test(TableView::class, ['tableClass' => CustomersTable::class, 'filter' => $filter])
            ->call('action', 'Filter')
            ->set('filter.currentField.name', 'id')
            ->assertSet('filter.currentField.name', 'id')
            ->call('callFilterMethod', 'getFieldData')
            ->assertSee('Previous')
            ->set('filter.conditionsFormFields.operator', 'Equal')
            ->set('filter.conditionsFormFields.value', $myCustomer->id)
            ->call('callFilterMethod', 'addConditions')
            ->assertSee($myCustomer->id);

        Livewire::test(TableView::class, ['tableClass' => CustomersTable::class, 'filter' => $filter])
            ->call('action', 'Filter')
            ->set('filter.currentField.name', 'id')
            ->assertSet('filter.currentField.name', 'id')
            ->call('callFilterMethod', 'getFieldData')
            ->assertSee('Previous')
            ->set('filter.conditionsFormFields.operator', 'Not Equal')
            ->set('filter.conditionsFormFields.value', $myCustomer->id)
            ->call('callFilterMethod', 'addConditions')
            ->assertSee($customer->fullName)
            ->assertDontSee($myCustomer->fullName);

        Livewire::test(TableView::class, ['tableClass' => CustomersTable::class, 'filter' => $filter])
            ->call('action', 'Filter')
            ->set('filter.currentField.name', 'id')
            ->assertSet('filter.currentField.name', 'id')
            ->call('callFilterMethod', 'getFieldData')
            ->assertSee('Previous')
            ->set('filter.conditionsFormFields.operator', 'Greater Than')
            ->set('filter.conditionsFormFields.value', 2)
            ->call('callFilterMethod', 'addConditions')
            ->assertSee($myCustomer->id);

        Livewire::test(TableView::class, ['tableClass' => CustomersTable::class, 'filter' => $filter])
            ->call('action', 'Filter')
            ->set('filter.currentField.name', 'id')
            ->assertSet('filter.currentField.name', 'id')
            ->call('callFilterMethod', 'getFieldData')
            ->assertSee('Previous')
            ->set('filter.conditionsFormFields.operator', 'Less Than')
            ->set('filter.conditionsFormFields.value', 10)
            ->call('callFilterMethod', 'addConditions')
            ->assertSee($myCustomer->id);

        Livewire::test(TableView::class, ['tableClass' => CustomersTable::class, 'filter' => $filter])
            ->call('action', 'Filter')
            ->set('filter.currentField.name', 'id')
            ->assertSet('filter.currentField.name', 'id')
            ->call('callFilterMethod', 'getFieldData')
            ->assertSee('Previous')
            ->set('filter.conditionsFormFields.operator', 'Greater Than or Equal')
            ->set('filter.conditionsFormFields.value', $customer->id)
            ->call('callFilterMethod', 'addConditions')
            ->assertSee($myCustomer->id);

        Livewire::test(TableView::class, ['tableClass' => CustomersTable::class, 'filter' => $filter])
            ->call('action', 'Filter')
            ->set('filter.currentField.name', 'id')
            ->assertSet('filter.currentField.name', 'id')
            ->call('callFilterMethod', 'getFieldData')
            ->assertSee('Previous')
            ->set('filter.conditionsFormFields.operator', 'Less Than or Equal')
            ->set('filter.conditionsFormFields.value', 3)
            ->call('callFilterMethod', 'addConditions')
            ->assertSee($myCustomer->id);
    }

    public function test_filter_users_by_date()
    {

        $createdBeforeNow = Customer::factory([
            'fullName' => 'Vladik',
            'email' => 'armen@mail.ru',
            'phoneNumber' => '+37496742106',
            'created_at' => Carbon::now()->subDays(10)
        ])->create();

        $currentCustomer = Customer::factory([
            'fullName' => 'Armen',
            'email' => 'armen@mail.ru',
            'phoneNumber' => '+37496742106',
            'created_at' => Carbon::now()->subDays(1)
        ])->create();

        $createdThen = Customer::factory([
            'fullName' => 'Kostya',
            'email' => 'armen@mail.ru',
            'phoneNumber' => '+37496742106',
        ])->create();

        $component = Livewire::test(Customers::class);
        $filter = $component->filter;

        Livewire::test(TableView::class, ['tableClass' => CustomersTable::class, 'filter' => $filter])
            ->call('action', 'Filter')
            ->set('filter.currentField.name', 'created_at')
            ->assertSet('filter.currentField.name', 'created_at')
            ->call('callFilterMethod', 'getFieldData')
            ->assertSee('Previous')
            ->set('filter.conditionsFormFields.operator', 'Greater Than or Equal')
            ->set('filter.conditionsFormFields.value', $currentCustomer->created_at->subMinute())
            ->call('callFilterMethod', 'addConditions')
            ->set('filter.conditionsFormFields.andOr', 'and')
            ->set('filter.conditionsFormFields.operator', 'Less Than or Equal')
            ->set('filter.conditionsFormFields.value', $currentCustomer->created_at->addMinute())
            ->call('callFilterMethod', 'addConditions')
            ->assertSee($currentCustomer->fullName)
            ->assertDontSee($createdThen->fullName)
            ->assertDontSee($createdBeforeNow->fullName);

            Livewire::test(TableView::class, ['tableClass' => CustomersTable::class, 'filter' => $filter])
            ->call('action', 'Filter')
            ->set('filter.currentField.name', 'created_at')
            ->assertSet('filter.currentField.name', 'created_at')
            ->call('callFilterMethod', 'getFieldData')
            ->assertSee('Previous')
            ->set('filter.conditionsFormFields.operator', 'Equal')
            ->set('filter.conditionsFormFields.value', $currentCustomer->created_at)
            ->call('callFilterMethod', 'addConditions')
            ->set('filter.conditionsFormFields.andOr', 'or')
            ->set('filter.conditionsFormFields.operator', 'Equal')
            ->set('filter.conditionsFormFields.value', $createdThen->created_at)
            ->call('callFilterMethod', 'addConditions')
            ->assertSee($currentCustomer->fullName)
            ->assertSee($createdThen->fullName)
            ->assertDontSee($createdBeforeNow->fullName);
    }

    public function createCustomers()
    {
        Customer::factory(count: 5)->create();
        Customer::factory([
            'fullName' => 'Armen',
            'email' => 'armen@mail.ru',
            'phoneNumber' => '+37496742106',
        ])->create();
    }
}
