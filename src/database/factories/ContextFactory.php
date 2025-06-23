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
        $users = User::all('id');
        $userId = count($users) > 0 ? $users->random(1)[0]->id : null;

        $context = Context::where('user_id', $userId)->get();

        return [
            'user_id' => $userId,
            'name' => fake()->word(),
            'is_root' => false,
            'parent_context_id' => (count($context) > 0 ?  $context->random(1)[0]->id : null),
            'order' => fake()->numberBetween(1, 999),
        ];
    }
}
