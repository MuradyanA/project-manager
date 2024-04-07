<?php

namespace Tests\Feature;

use App\Livewire\Customers;
use App\Livewire\TableView;
use App\Models\Customer;
use App\Models\Project;
use App\Models\User;
use App\Services\TableViews\CustomersTable;
use Database\Factories\ProjectFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Livewire\Livewire;


class CustomerTest extends TestCase
{

    // use RefreshDatabase;

    // public function test_that_customers_page_is_available_only_for_authenticated_users(): void
    // {
    //     $this->get('/customers')
    //         ->assertStatus(302)
    //         ->assertRedirect('/signIn');

    //     $user = User::factory()->create();

    //     $this->actingAs($user)->get('/customers')->assertStatus(200);
    // }

    // public function test_that_users_list_show_on_the_page()
    // {
    //     $customers = Customer::factory(count: 10)->create();
    //     Livewire::test(Customers::class)->assertSee($customers->first()->fullName);
    //     Livewire::test(TableView::class, ['tableClass' => CustomersTable::class])->assertViewHas('data', function ($items) {
    //         return count($items) == 10;
    //     });
    // }

    // public function test_that_customer_can_be_edited()
    // {
    //     $customer = Customer::factory(count: 10)->create()->first();


    //     $table = Livewire::test(TableView::class, ['tableClass' => CustomersTable::class])
    //         ->set('selectedItems', [$customer->id])
    //         ->call('action', 'Edit')
    //         ->assertDispatched('Edit');


    //     $customerForm = Livewire::test(Customers::class)->dispatch('Edit', [$customer->id])
    //         ->assertSet('customerForm.id', $customer->id)
    //         ->assertSet('customerForm.fullName', $customer->fullName)
    //         ->assertSet('customerForm.email', $customer->email);

    //     $customerForm->set('customerForm.fullName', null)->call('saveCustomer')->assertHasErrors('customerForm.fullName');
    // }

    // public function test_that_customer_can_be_created()
    // {
    //     Livewire::test(Customers::class)
    //         ->set('customerForm.fullName', 'Jhen Kolins')
    //         ->set('customerForm.email', 'JhenKolins@mail.com')
    //         ->set('customerForm.phoneNumber', '+37412345678')
    //         ->call('saveCustomer');

    //     $this->assertDatabaseHas('customers', [
    //         'fullName' => 'Jhen Kolins',
    //     ]);
    // }

    // public function test_that_customer_can_be_deleted()
    // {
    //     $customer = Customer::factory(count: 10)->create()->first();
    //     $project = Project::factory(count: 1)->create()->first();
    //     // dd($project->customerId, Customer::find($project->customerId));

    //     $table = Livewire::test(TableView::class, ['tableClass' => CustomersTable::class])
    //         ->set('selectedItems', [$project['customerId']])
    //         ->call('action', 'Delete')
    //         ->assertDispatched('Delete');

    //     $customers = Livewire::test(Customers::class)
    //         ->dispatch('Delete', [$project->customerId]);

    //     $this->assertDatabaseHas('customers', [
    //         'id' => $project->customerId,
    //     ]);

    //     $customers = Livewire::test(Customers::class)
    //         ->dispatch('Delete', [$customer->id]);

    //     $this->assertDatabaseMissing('customers', [
    //         'id' => $customer->id,
    //     ]);
    // }
}
