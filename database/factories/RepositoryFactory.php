<?php

namespace Database\Factories;

use App\Models\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

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
            'display_name' => fake()->name(),
            'package_name' => fake()->name(),
            'feed_url' => fake()->url(),
            'website_url' => fake()->url(),
            'description' => fake()->text(),
            'tags' => fake()->word(),
            'max_age_days' => fake()->randomNumber(),
            'username' => fake()->email(),
            'password' => Hash::make(fake()->password()),
        ];
    }
}
