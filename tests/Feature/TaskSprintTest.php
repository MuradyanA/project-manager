<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Sprint;
use Livewire\Livewire;


class TaskSprintTest extends TestCase
{
    // use RefreshDatabase;

    // public function test_that_tasks_page_is_available_only_for_authenticated_users(): void
    // {
    //     $sprint = Sprint::factory()->create();
    //     $this->get("/projects/$sprint->projectId/sprints")
    //         ->assertStatus(302)
    //         ->assertRedirect('/signIn');

    //     $user = User::factory()->create();

    //     $this->actingAs($user)->get("/projects/$sprint->projectId/sprints")->assertStatus(200);
    // }

    // public function test_that_user_can_be_added_to_the_task()
    // {
    //     Livewire::test(User::class)
    //         ->set('customerForm.fullName', 'Jhen Kolins')
    //         ->set('customerForm.email', 'JhenKolins@mail.com')
    //         ->set('customerForm.phoneNumber', '+37412345678')
    //         ->call('saveCustomer');

    //     $this->assertDatabaseHas('customers', [
    //         'fullName' => 'Jhen Kolins',
    //     ]);
    // }

}
