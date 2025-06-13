<?php

namespace App\Jobs;

use App\Actions\ConvertImage;
use App\Models\Thumbnail;
use App\Services\ImageService;
use App\Services\StorageService;
use App\Services\ThumbnailService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

class ProcessThumbnail implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(protected Thumbnail $thumbnail)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(
        ImageService $imageService,
        StorageService $storageService,
    ): void {
        $absoluteThumbnailPath = $storageService->path($this->thumbnail->name);
        $scaledImage = $imageService->scale($absoluteThumbnailPath);
        $file = $storageService->saveEncodedImage($scaledImage);

        if ($file) {
            $oldThumbnail = $this->thumbnail->name;

            $this->thumbnail->update([
                'name' => $file,
                'is_processed' => true,
            ]);

            $deleteRes = $storageService->delete($oldThumbnail);
        }
    }
}
