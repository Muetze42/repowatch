<?php

namespace Database\Factories;

use App\Models\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Repository>
 */
class RepositoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'provider_id' => Provider::factory(),

            'display_name' => fake()->name(),
            'package_name' => fake()->name(),
            'custom_feed_url' => fake()->url(),
            'website_url' => fake()->url(),
            'description' => fake()->text(),
            'tags' => fake()->word(),
            'max_age_days' => fake()->randomNumber(),
        ];
    }
}
