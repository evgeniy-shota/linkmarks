<?php

namespace Tests\Feature;

use App\Models\Bookmark;
use App\Models\Context;
use App\Models\Thumbnail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BookmarksTest extends TestCase
{
    use RefreshDatabase;

    /*
    *   @param array dependencies with required: 
    *       rootContext => Context
    *       thumbnail => Thumbnail
    */
    private function getBookmarkData(array $dependencies = []): array
    {
        $bookmarkData = [
            'context_id' => $dependencies['rootContext']->id,
            'link' => $dependencies['link'] ?? fake()->url(),
            'name' => $dependencies['name'] ?? fake()->word(),
            'thumbnail_id' => $dependencies['thumbnails']->random(1)[0]->id,
            'order' => $dependencies['order'] ?? fake()->numberBetween(2, 999),
        ];

        if (isset($dependencies['user']?->id)) {
            $bookmarkData['user_id'] = $dependencies['user']->id;
        }

        return $bookmarkData;
    }

    /**
     * bookmarks test
     */
    public function test_show_all_user_bookmarks_success(): void
    {
        $dependencies1 = $this->prepareDependencies();
        $dependencies2 = $this->prepareDependencies();
        $numberBookmarks1 = 5;
        $numberBookmarks2 = 6;
        $bookmarks1 = $this->createBookmarks(
            $dependencies1['user']->id,
            $dependencies1['rootContext']->id,
            $numberBookmarks1,
        );
        $bookmarks2 = $this->createBookmarks(
            $dependencies2['user']->id,
            $dependencies2['rootContext']->id,
            $numberBookmarks2,
        );
        $response = $this->actingAs($dependencies1['user'])
            ->getJson(route(
                'showContextData',
                $dependencies1['rootContext']
            ));

        $response->assertSuccessful()
            ->assertJsonCount($numberBookmarks1, 'data');
    }

    public function test_show_user_bookmark_success(): void
    {
        $dependencies = $this->prepareDependencies();
        $bookmark = $this->createBookmarks(
            $dependencies['user']->id,
            $dependencies['rootContext']->id,
        );
        $response = $this->actingAs($dependencies['user'])
            ->getJson(route('bookmarks.show', $bookmark[0]->id));

        $response->assertSuccessful()->assertJson([
            'data' => [
                'id' => $bookmark[0]->id
            ]
        ]);
    }

    public function test_show_other_user_bookmark_fail(): void
    {
        $dependencies = $this->prepareDependencies();
        $dependencies2 = $this->prepareDependencies();
        $bookmark = $this->createBookmarks(
            $dependencies['user']->id,
            $dependencies['rootContext']->id,
        );
        $response = $this->actingAs($dependencies2['user'])
            ->getJson(route('bookmarks.show', $bookmark->random(1)[0]->id));

        $response->assertNotFound()->assertJson([
            'message' => 'Not found'
        ]);
    }

    public function test_create_bookmark_with_correct_data_success(): void
    {
        $dependencies = $this->prepareDependencies();
        $bookmarkData = $this->getBookmarkData($dependencies);

        $response = $this->actingAs($dependencies['user'])->post(
            route('bookmarks.store'),
            $bookmarkData
        );

        $response->assertCreated();

        $this->assertDatabaseHas('bookmarks', [
            'link' => $bookmarkData['link'],
            'name' => $bookmarkData['name'],
            'context_id' => $bookmarkData['context_id'],
        ]);
    }

    public function test_create_bookmark_with_uploading_image_success(): void
    {
        $dependencies = $this->prepareDependencies();
        $bookmarkData = $this->getBookmarkData($dependencies);
        $image = UploadedFile::fake()->image('image.png');
        $bookmarkData['thumbnailFile'] = $image;
        Storage::fake('public');
        $response = $this->actingAs($dependencies['user'])->post(
            route('bookmarks.store'),
            $bookmarkData
        );

        $response->assertCreated();

        $this->assertDatabaseHas('bookmarks', [
            'link' => $bookmarkData['link'],
            'name' => $bookmarkData['name'],
            'context_id' => $bookmarkData['context_id'],
        ]);
        Storage::disk('public')->assertExists($image->hashName());
    }

    public function test_create_bookmark_with_incorrect_data_fail(): void
    {
        $dependencies = $this->prepareDependencies();
        $bookmarkData = $this->getBookmarkData([...$dependencies, 'link' => 'qwerty..com']);

        $response = $this->actingAs($dependencies['user'])->post(
            route('bookmarks.store'),
            $bookmarkData
        );

        $response->assertSessionHasErrors(['link']);

        $this->assertDatabaseMissing('bookmarks', [
            'link' => $bookmarkData['link'],
            'name' => $bookmarkData['name'],
            'context_id' => $bookmarkData['context_id'],
        ]);
    }

    public function test_update_bookmark_with_correct_data_success(): void
    {
        $dependencies = $this->prepareDependencies();
        $bookmarkData = $this->getBookmarkData($dependencies);
        $bookmark = Bookmark::create($bookmarkData);
        $bookmarkData['name'] .= 'qwerty';
        $bookmarkData['link'] = 'https://someurl.online';

        $response = $this->actingAs($dependencies['user'])->put(
            route('bookmarks.store') . "/$bookmark->id",
            $bookmarkData
        );

        $response->assertSuccessful();

        $this->assertDatabaseHas('bookmarks', [
            'user_id' => $bookmarkData['user_id'],
            'link' => $bookmarkData['link'],
            'name' => $bookmarkData['name'],
            'context_id' => $bookmarkData['context_id'],
        ]);
    }

    public function test_update_bookmark_with_incorrect_data_fail(): void
    {
        $dependencies = $this->prepareDependencies();
        $bookmarkData = $this->getBookmarkData($dependencies);
        $bookmark = Bookmark::create($bookmarkData);
        $bookmarkData['link'] = 'qwerty';

        $response = $this->actingAs($dependencies['user'])->put(
            route('bookmarks.store') . "/$bookmark->id",
            $bookmarkData
        );

        $response->assertSessionHasErrors(['link']);

        $this->assertDatabaseMissing('bookmarks', [
            'user_id' => $bookmarkData['user_id'],
            'link' => $bookmarkData['link'],
            'name' => $bookmarkData['name'],
            'context_id' => $bookmarkData['context_id'],
        ]);
    }

    public function test_update_other_user_bookmark_fail(): void
    {
        $dependencies = $this->prepareDependencies();
        $someUser = $this->createUser();
        $bookmarkData = $this->getBookmarkData($dependencies);
        $bookmark = Bookmark::create($bookmarkData);
        $bookmarkData['name'] .= 'qwerty';
        $bookmarkData['link'] = 'https://someurl.online';

        $response = $this->actingAs($someUser)->put(
            route('bookmarks.store') . "/$bookmark->id",
            $bookmarkData
        );

        $response->assertNotFound()->assertJson(['message' => 'Not found']);

        $this->assertDatabaseMissing('bookmarks', [
            'user_id' => $bookmarkData['user_id'],
            'link' => $bookmarkData['link'],
            'name' => $bookmarkData['name'],
            'context_id' => $bookmarkData['context_id'],
        ]);
    }

    public function test_delete_bookmark_success(): void
    {
        $dependencies = $this->prepareDependencies();
        $bookmarkData = $this->getBookmarkData($dependencies);
        $bookmark = Bookmark::create($bookmarkData);

        $response = $this->actingAs($dependencies['user'])->delete(route('bookmarks.destroy', $bookmark->id));
        $response->assertSuccessful();
        $this->assertDatabaseMissing('bookmarks', [
            'id' => $bookmark->id,
        ]);
    }

    public function test_delete_other_user_bookmark_fail(): void
    {
        $dependencies = $this->prepareDependencies();
        $someUser = $this->createUser();
        $bookmarkData = $this->getBookmarkData($dependencies);
        $bookmark = Bookmark::create($bookmarkData);

        $response = $this->actingAs($someUser)->delete(route('bookmarks.destroy', $bookmark->id));
        $response->assertNotFound()->assertJson(['message' => 'Not found']);
        $this->assertDatabaseHas('bookmarks', [
            'id' => $bookmark->id,
        ]);
    }
}
