<?php

namespace Tests\Feature;

use App\Models\Tag;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class TagsTest extends TestCase
{
    use RefreshDatabase;

    private function getTagData(int $userId): array
    {
        $tagData = [
            'name' => fake()->text(8),
            'user_id' => $userId,
        ];

        return $tagData;
    }
    /**
     * tag test
     */
    public function test_show_all_user_tags_success(): void
    {
        $users = User::factory()->count(2)->create();
        $firstUserTags = Tag::factory()->count(5)
            ->create(['user_id' => $users[0]]);
        $secondUserTags = Tag::factory()->count(5)
            ->create(['user_id' => $users[1]]);

        $response = $this->actingAs($users[0])->getJson(route('tags.index'));
        $response->assertSuccessful()->assertJsonCount(5, 'data');
    }

    public function test_show_user_tag_success(): void
    {
        $users = User::factory()->count(2)->create();
        $firstUserTags = Tag::factory()->count(5)
            ->create(['user_id' => $users[0]]);
        $secondUserTags = Tag::factory()->count(5)
            ->create(['user_id' => $users[1]]);

        $response = $this->actingAs($users[0])->getJson(route('tags.show', $firstUserTags[0]->id));
        $response->assertSuccessful()->assertJson([
            'data' => [
                'id' => $firstUserTags[0]->id,
                'name' => $firstUserTags[0]->name,
            ],
        ]);
    }

    public function test_show_other_user_tag_fail(): void
    {
        $users = User::factory()->count(2)->create();
        $firstUserTags = Tag::factory()->count(5)
            ->create(['user_id' => $users[0]]);
        $secondUserTags = Tag::factory()->count(5)
            ->create(['user_id' => $users[1]]);

        $response = $this->actingAs($users[1])->getJson(route('tags.show', $firstUserTags[0]->id));
        $response->assertNotFound()->assertJson(
            ['message' => 'Not found']
        );
    }

    public function test_create_tag_with_correct_data_success(): void
    {
        $user = User::factory()->create();
        $tagData = $this->getTagData($user->id);
        $response = $this->actingAs($user)->post(route('tags.store'), $tagData);

        $response->assertCreated();
        $this->assertDatabaseHas('tags', [
            'user_id' => $tagData['user_id'],
            'name' => $tagData['name'],
        ]);
    }

    public function test_create_tag_with_incorrect_data_fail(): void
    {
        $user = User::factory()->create();
        $tagData = $this->getTagData($user->id);
        $tagData['name'] = '';
        $response = $this->actingAs($user)->post(route('tags.store'), $tagData);

        $response->assertSessionHasErrors('name');
        $this->assertDatabaseMissing('tags', [
            'user_id' => $tagData['user_id'],
            'name' => $tagData['name'],
        ]);
    }

    public function test_update_tag_with_correct_data_success(): void
    {
        $user = User::factory()->create();
        $tagData = $this->getTagData($user->id);
        $tag = Tag::create($tagData);
        $tagData['name'] = 'updated';
        $response = $this->actingAs($user)->put(route('tags.update', $tag->id), $tagData);

        $response->assertSuccessful()->assertJson([
            'data' => [
                'name' => $tagData['name'],
            ],
        ]);

        $this->assertDatabaseHas('tags', [
            'user_id' => $tagData['user_id'],
            'name' => $tagData['name'],
        ]);
    }

    public function test_update_tag_with_incorrect_data_fail(): void
    {
        $user = User::factory()->create();
        $tagData = $this->getTagData($user->id);
        $tag = Tag::create($tagData);
        $tagData['name'] = '';
        $response = $this->actingAs($user)
            ->put(route('tags.update', $tag->id), $tagData);

        $response->assertSessionHasErrors('name');
        $this->assertDatabaseMissing('tags', [
            'user_id' => $tagData['user_id'],
            'name' => $tagData['name'],
        ]);
    }

    public function test_update_tag_other_user_fail(): void
    {
        $users = User::factory()->count(2)->create();
        $tagData = $this->getTagData($users[0]->id);
        $tag = Tag::create($tagData);
        $response = $this->actingAs($users[1])
            ->put(route('tags.update', $tag->id), $tagData);
        $tagData['name'] = 'updated';

        $response->assertNotFound()
            ->assertJson(['message' => 'Not found']);
        $this->assertDatabaseMissing('tags', [
            'user_id' => $tagData['user_id'],
            'name' => $tagData['name'],
        ]);
    }

    public function test_delete_tag_success(): void
    {
        $user = User::factory()->create();
        $tagData = $this->getTagData($user->id);
        $tag = Tag::create($tagData);
        $response = $this->actingAs($user)
            ->delete(route('tags.destroy', $tag->id));

        $response->assertSuccessful();
        $this->assertDatabaseMissing('tags', [
            'id' => $tag->id,
        ]);
    }

    public function test_delete_tag_other_user_fail(): void
    {
        $users = User::factory()->count(2)->create();
        $tagData = $this->getTagData($users[0]->id);
        $tag = Tag::create($tagData);
        $response = $this->actingAs($users[1])
            ->delete(route('tags.destroy', $tag->id));

        $response->assertNotFound();
        $this->assertDatabaseHas('tags', [
            'id' => $tag->id,
        ]);
    }
}
