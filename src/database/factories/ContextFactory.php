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
        $userId = User::all()->random(1)[0]->id;

        $context = Context::where('user_id', $userId)->get();

        // dd($context);
        // dd(count($context) === 1 ? $context[0]->id : $context->random(1)[0]->id);

        return [
            'user_id' => $userId,
            'name' => fake()->word(),
            'is_root' => false,
            'parent_context_id' => (count($context) === 1 ? $context[0]->id : $context->random(1)[0]->id),
            'enabled' => true,
            'order' => fake()->numberBetween(1, 999),
        ];
    }
}
