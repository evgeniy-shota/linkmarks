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
        dump($files);
        $data = array_map(function ($item) {
            return [
                'name' => $item,
                'source' => ThumbnailSource::Default->value,
            ];
        }, $files);
        dump($data);
        $thumbnails = DB::table('thumbnails')->insert($data);

        dump($thumbnails);
    }
}
