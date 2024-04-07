<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Sprint;
use App\Models\Request;
use App\Models\Project;
use Carbon\Carbon;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $project = Project::factory()->create();
        $request = Request::factory()->create();
        $sprint = Sprint::factory(['projectId' => $project->id, 'start' => Carbon::now(), 'end' => Carbon::now()->addDays(10)])->create(); 
        return [
            'projectId' => $project->id,
            'requestId' => $request->id,
            'sprintId' => $sprint->id,
            'title' => fake()->city(),
            'body' => fake()->realText($maxNbChars = 200, $indexSize = 2),
            'status' => fake()->randomElement(['Proposed', 'Active', 'Resolved', 'Closed', 'Rejected']),
            'start' => $sprint->start,
            'end' => $sprint->end
        ];
    }
    protected function getSprint()
    {
        return Sprint::where('projectId', Project::first()->id)->latest()->get()->first();
    }
}
