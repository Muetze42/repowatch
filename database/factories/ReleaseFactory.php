<?php

namespace Database\Factories;

use App\Models\Repository;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Release>
 */
class ReleaseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'repository_id' => Repository::factory(),

            'version' => fake()->semver(),
            'version_normalized' => fake()->semver(),
            'require' => (array) fake()->words(),
            'require_dev' => (array) fake()->words(),
            'files' => (array) fake()->words(),
        ];
    }
}
