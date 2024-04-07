<?php

namespace Tests\Feature;

use App\Livewire\AllRequests;
use App\Models\Request;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\User;
use App\Models\Sprint;
use App\Models\Customer;
use App\Models\Project;
use App\Services\TableViews\ChangeRequestsTable;
use Tests\TestCase;
use Livewire\Livewire;
use App\Livewire\TableView;


class RequestsTest extends TestCase
{
    public function test_that_requests_page_is_available_only_for_authenticated_users(): void
    {
        $request = Request::factory(count: 1)->create()->first();
        // dd($request);
        $this->get("/projects/$request->projectId")
            ->assertStatus(302)
            ->assertRedirect('/signIn');

        $user = User::factory()->create();

        $this->actingAs($user)->get("/projects/$request->projectId")->assertStatus(200)->assertSee($request->title);
    }

    public function test_that_new_sprint_dialog_is_opened_after_dispatching_the_event()
    {
        list($project, $customer, $requests, $requestsIds) = $this->create_project_customer_and_requests();
        $table = Livewire::test(TableView::class, ['tableClass' => ChangeRequestsTable::class])
            ->set('selectedItems', [$requestsIds])
            ->call('action', 'AddToNewSprint')
            ->assertDispatched('AddToNewSprint');

        $customerForm = Livewire::test(AllRequests::class, ['project' => $project])->dispatch('AddToNewSprint', [$requestsIds])
            ->assertSet('isOpenedNewSprintForm', true);
    }

    public function test_that_new_sprint_and_new_tasks_can_be_created()
    {
        list($project, $customer, $requests, $requestsIds) = $this->create_project_customer_and_requests();
        $customerForm = Livewire::test(AllRequests::class, ['project' => $project])
            ->set('isOpenedNewSprintForm', true)
            ->set('selectedChangeRequests', $requestsIds)
            ->call('createSprintAndTasks')
            ->assertHasErrors('sprintForm.start')
            ->assertHasErrors('sprintForm.end')
            ->set('sprintForm.start', '2024-02-19')
            ->assertHasNoErrors('sprintForm.start')
            ->set('sprintForm.end', '2024-02-14')
            ->assertHasNoErrors('sprintForm.start');
    }

    public function test_that_task_can_be_created_and_added_into_current_sprint()
    {
        list($project, $customer, $requests, $requestsIds) = $this->create_project_customer_and_requests();
        
        $sprint = Sprint::factory(count: 1)->create(['projectId' => $project->id])->toArray();

        Livewire::test(AllRequests::class, ['project' => $project])
            ->call('addTasksToSprint', $requestsIds);
            
        $this->assertDatabaseCount('tasks', 10);
    }

    public function create_project_customer_and_requests()
    {
        $project = Project::factory(count: 1)->create()->first();
        $customer = Customer::factory(count: 1)->create()->first();
        $requests = Request::factory(count: 10)->create([
            'projectId' => $project->id,
            'requesterId' => $customer->id,
        ]);
        $requestsIds = $requests->pluck('id')->toArray();
        return [$project, $customer, $requests, $requestsIds];
    }
}
