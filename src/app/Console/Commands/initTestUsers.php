<?php

namespace App\Console\Commands;

use App\Models\Bookmark;
use App\Models\Context;
use App\Models\Tag;
use App\Models\User;
use App\Services\BookmarkService;
use App\Services\ContextService;
use App\Services\TagService;
use App\Services\ThumbnailService;
use App\Services\UserServices;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class initTestUsers extends Command
{
    public function __construct(
        protected UserServices $userServices,
        protected TagService $tagService,
        protected ContextService $contextService,
        protected BookmarkService $bookmarkService,
        protected ThumbnailService $thumbnailService,
    ) {
        parent::__construct();
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:init-test-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates test users with test data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $testUsers = DB::table('test_users')
            ->select(
                'name',
                'email',
                'password',
                'is_admin'
            )->where('is_enabled', true)->get();

        if (!isset($testUsers) || count($testUsers) == 0) {
            $this->warn('No test users found.');
            return;
        }

        $users = [];
        foreach ($testUsers as $testUser) {
            $user = User::where('email', $testUser->email)->first();

            if (isset($user)) {
                $users[] = $user;
                $this->contextService->getAllContexts($user->id, true)->delete();
                Tag::where('user_id', $user->id)->delete();
                Bookmark::where('user_id', $user->id)->delete();
                continue;
            }

            $users[] = $this->userServices->create(
                array_merge((array)$testUser, ['email_verified_at' => now()])
            );
        }

        $testTags = DB::table('test_tags')
            ->select(
                'name'
            )->where('is_enabled', true)->get()->toArray();

        if (!isset($testTags) || count($testTags) == 0) {
            $this->warn('No test tags found.');
            return;
        }

        $testContexts = DB::table('test_contexts')
            ->select(
                'name',
                'is_root',
                'tags',
                'order'
            )->where('is_enabled', true)->get()->toArray();

        if (!isset($testContexts) || count($testContexts) == 0) {
            $this->warn('No test context found.');
            return;
        }

        $testBookmarks = DB::table('test_bookmarks')
            ->select(
                'context',
                'link',
                'name',
                'tags',
                'order'
            )->where('is_enabled', true)->get()->toArray();

        if (!isset($testBookmarks) || count($testBookmarks) == 0) {
            $this->warn('No test bookmarks found.');
            return;
        }

        foreach ($users as $user) {
            $rootContext = $this->contextService->getRootContext($user->id);

            DB::table('tags')->insert(array_map(
                function ($tag) use ($user) {
                    $tag = (array)$tag;
                    $tag['user_id'] = $user->id;
                    return $tag;
                },
                $testTags
            ));

            $tags = Tag::where('user_id', $user->id)->get();
            // $tags = array_combine((array)($tags->pluck('name')), (array)($tags->pluck('id')));
            $tags = (array)$tags->pluck('id', 'name')->all();
            $contextOrder = 1;

            foreach ($testContexts as $testContext) {
                $testContext = (array)$testContext;
                $testContext['user_id'] = $user->id;
                $testContext['parent_context_id'] = $rootContext->id;
                $testContext['order'] += $contextOrder++;
                $testContext['tags'] = $this->getTagsIdFromString($testContext['tags'], $tags);
                $this->contextService->createContext($testContext, $user->id);
            }

            $contexts = $this->contextService->getAllContexts($user->id, true)
                ->get();
            $contexts = $contexts->pluck('id', 'name');
            $bookmarks = [];

            foreach ($testBookmarks as $bookmark) {
                $data = (array)$bookmark;
                $data['context_id'] = $contexts[$bookmark->context] ??
                    $rootContext->id;
                $data['tags'] = $this->getTagsIdFromString($data['tags'], $tags);

                $parsedLink = parse_url($data['link']);
                $thumbnail = $this->thumbnailService->getByAssociations(
                    $parsedLink['host'],
                    $user->id
                );

                if (isset($thumbnail) && count($thumbnail) > 0) {
                    $data['thumbnail_id'] = $thumbnail[0]->id;
                }

                $bookmarks[] = $this->bookmarkService->createBookmark(
                    $data,
                    $user->id
                );
            }
            dump(memory_get_peak_usage() / 1024 / 1024 . 'Mb');
        }
    }

    protected function getTagsIdFromString(?string $tagsNames, array $tagsNI)
    {
        if (!isset($tagsNames)) {
            return null;
        }

        $tagsName = explode(',', $tagsNames);
        $result = [];

        foreach ($tagsName as $name) {
            if (
                !isset($name) || strlen($name) == 0
                || !array_key_exists($name, $tagsNI)
            ) {
                continue;
            }

            $result[] = $tagsNI[$name];
        }
        return $result;
    }
}
