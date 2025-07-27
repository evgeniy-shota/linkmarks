<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Enums\ThumbnailSource;
use App\Models\Bookmark;
use App\Models\Thumbnail;
use App\Services\BookmarkAutocompleteService;
use App\Services\BookmarkService;
use App\Services\StorageService;
use App\Services\ThumbnailService;
use Illuminate\Console\Command;

class uploadUnexistsThumbnails extends Command
{
    public function __construct(
        protected ThumbnailService $thumbnailService,
        protected StorageService $storageService,
        protected BookmarkService $bookmarkService,
        protected BookmarkAutocompleteService $bookmarkAS
    ) {
        parent::__construct();
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:upload-unexists-thumbnails {userid? : int}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks and downloads non-existent thumbnails';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('userid');

        if (isset($userId)) {
            $userId = filter_var($userId, FILTER_VALIDATE_INT);

            if ($userId === false || $userId < 1) {
                $this->error('Invalid userid argument');
                return;
            }
        }

        $files = array_flip($this->storageService->getFiles('thumbnails'));
        $missingThumbnails = [];

        Thumbnail::where("source", '!=', ThumbnailSource::Default->value)
            ->select('id', 'name', 'source')
            ->chunk(
                100,
                function ($collection) use (&$missingThumbnails, $files) {
                    $missingThumbnails = array_merge(
                        $missingThumbnails,
                        array_diff_key(array_combine(
                            $collection->pluck('name')->toArray(),
                            $collection->pluck('id')->toArray()
                        ), $files)
                    );
                }
            );

        foreach ($missingThumbnails as $thumbnailName => $thumbnailId) {
            $bookmarks = Bookmark::where('thumbnail_id', $thumbnailId)->get();

            foreach ($bookmarks as $bookmark) {
                $parsedLink = parse_url($bookmark['link']);
                $associatThumbnails = $this->thumbnailService
                    ->getByAssociations($parsedLink['host'], $userId);


                $thumbnail = $associatThumbnails->first(
                    function ($value) use ($thumbnailId) {
                        return $value->id !== $thumbnailId;
                    }
                );

                if (!isset($thumbnail)) {
                    $thumbnail = $this->bookmarkAS->getSiteFavicon(
                        $bookmark['link'],
                        $parsedLink['host']
                    );
                }

                $bookmark->thumbnail_id = $thumbnail?->id
                    ?? $this->thumbnailService->getDefault()->id;
                $bookmark->save();
            }
            Thumbnail::where('id', $thumbnailId)->delete();
        }
    }
}
