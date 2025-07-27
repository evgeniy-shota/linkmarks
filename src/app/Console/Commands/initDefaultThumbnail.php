<?php

namespace App\Console\Commands;

use App\Enums\ThumbnailSource;
use App\Models\Thumbnail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class initDefaultThumbnail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:init-default-thumbnail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description =
    'Adds default thumbnails from thumbnails-default to db thumbnails';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $files = Storage::disk('public')->files('thumbnails-default');

        foreach ($files as $fileName) {
            $offset = strpos($fileName, '/') + 1;
            $length = strlen($fileName)
                - (strlen($fileName) - strrpos($fileName, '.')) - $offset;

            DB::table('thumbnails')->updateOrInsert(
                ['name' => $fileName, 'source' => ThumbnailSource::Default->value,],
                [
                    'associations' => substr(
                        $fileName,
                        $offset,
                        $length
                    ),
                    'is_processed' => true
                ]
            );
        }
    }
}
