<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Project;
use App\Models\Customer;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Request>
 */
class RequestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'projectId' => Project::factory(),
            'requesterId' => Customer::factory(),
            'title' => fake()->catchPhrase(),
            'request' => fake()->realText($maxNbChars = 255, $indexSize = 2)
        ];
    }
}
