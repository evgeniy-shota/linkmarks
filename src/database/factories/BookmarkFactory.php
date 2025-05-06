<?php

namespace Database\Factories;

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
        $thumbnails = Thumbnail::all('id')->toArray();
        return [
            'user_id' => array_rand(User::all('id')->toArray(), 1),
            'context_id' => array_rand(User::all('id')->toArray(), 1),
            'link' => fake()->url(),
            'name' => fake()->company(),
            'thumbnail_id' => array_rand($thumbnails, 1),
            'is_enabled' => true,
            'order' => fake()->numberBetween(1, 999),
        ];
    }
}
