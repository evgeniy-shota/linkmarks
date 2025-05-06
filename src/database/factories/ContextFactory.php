<?php

namespace Database\Factories;

use App\Models\Context;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Context>
 */
class ContextFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userId = array_rand(User::all()->toArray(), 1);

        return [
            'user_id' => $userId,
            'name' => fake()->word(),
            'is_root' => false,
            'parent_context_id' => array_rand(Context::where('user_id', $userId)->select('id')->get(), 1),
            'enabled' => true,
            'order' => fake()->numberBetween(1, 999),
        ];
    }
}
