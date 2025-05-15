<?php

namespace App\Jobs;

use App\Actions\ConvertImage;
use App\Models\Thumbnail;
use App\Services\ImageService;
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
    public function handle(ImageService $imageService, ThumbnailService $thumbnailService): void
    {
        $absoluteThumbnailPath = Storage::disk('public')->path($this->thumbnail->name);
        $scaledImage = $imageService->scale($absoluteThumbnailPath);
        dd($scaledImage);
        $newPath = $thumbnailService->saveFromEncodedImage($scaledImage);

        if ($newPath) {
            $oldThumbnail = $this->thumbnail->name;

            $this->thumbnail->update([
                'name' => $newPath,
                'is_processed' => true,
            ]);

            Storage::disk('public')->delete($oldThumbnail);
        }
    }
}
