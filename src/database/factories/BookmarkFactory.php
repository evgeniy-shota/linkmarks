<?php

namespace Database\Factories;

use App\Models\Context;
use App\Models\Thumbnail;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Bookmark>
 */
class BookmarkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $thumbnail_id = Thumbnail::all('id')->random(1)[0];
        $user_id = User::all('id')->random(1)[0]->id;

        return [
            'user_id' => $user_id,
            'context_id' => Context::where('user_id', $user_id)->get()->random(1)[0]->id,
            'link' => fake()->url(),
            'name' => fake()->company(),
            'thumbnail_id' => $thumbnail_id,
            'is_enabled' => true,
            'order' => fake()->numberBetween(1, 999),
        ];
    }
}
