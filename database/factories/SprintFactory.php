<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\Sprint;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Sprint>
 */
class SprintFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $existingSprint = Sprint::latest()->get()->first();
        return [
            'projectId' => Project::factory(),
            'sprint' => function()use($existingSprint){
                return  $existingSprint == null ? 1 : $existingSprint->id + 1 ;
            },
            'start' => function()use($existingSprint){
                return $existingSprint == null ? Carbon::now() : Carbon::parse($existingSprint->end)->addDay();
            } ,
            'end' => function()use($existingSprint){
                return $existingSprint == null ? Carbon::now()->addDay() : Carbon::parse($existingSprint->end)->addDays(21);
            } ,
        ];
    }
}
