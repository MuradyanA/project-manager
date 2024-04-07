<?php

namespace Tests\Feature;

use App\Livewire\ProjectSprints;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Sprint;
use App\Models\User;
use App\Models\Project;
use App\Models\Task;
use App\Services\Exceptions\ServiceException;
use Livewire\Livewire;

class SprintsTest extends TestCase
{
    use RefreshDatabase;
    public function test_that_sprints_page_is_available_only_for_authenticated_users(): void
    {
        $sprint = Sprint::factory(count: 1)->create()->first();
        $this->get("/projects/$sprint->projectId/sprints")
            ->assertStatus(302)
            ->assertRedirect('/signIn');

        $user = User::factory()->create();

        $this->actingAs($user)->get("/projects/$sprint->projectId/sprints")->assertStatus(200);
    }

    public function test_that_sprint_can_be_deleted()
    {
        $task = Task::factory(1, ['status' => 'Proposed'])->create()->first();

        Livewire::test(ProjectSprints::class, ['project' => Project::findOrFail($task->projectId)])->dispatch('Delete', [$task->sprintId]);
        $this->assertDatabaseCount('tasks', 0);
    }

    public function test_that_sprint_cant_be_deleted_if_it_contains_not_proposed_tasks()
    {
        $task = Task::factory(1, ['status' => 'Active'])->create()->first();

        Livewire::test(ProjectSprints::class, ['project' => Project::findOrFail($task->projectId)])->dispatch('Delete', [$task->sprintId]);
        $this->assertDatabaseCount('tasks', 1);
    }

    public function test_that_test_cant_be_deleted_if_it_isnt_the_last_one()
    {
        Sprint::whereNotNull('id')->delete();
        $sprint1 = Sprint::factory()->create()->first();
        $sprint2 = Sprint::factory(['projectId' => $sprint1->projectId])->create()->first();

        Livewire::test(ProjectSprints::class, ['project' => Project::findOrFail($sprint1->projectId)])->dispatch('Delete', [$sprint1->id]);
        $this->assertDatabaseCount('sprints', 2);

    }
}
