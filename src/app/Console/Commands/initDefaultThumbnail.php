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
        // $data = array_map(function ($item) {
        //     $offset = strpos($item, '/') + 1;
        //     $length = strlen($item)
        //         - (strlen($item) - strrpos($item, '.')) - $offset;
        //     return [
        //         'name' => $item,
        //         'source' => ThumbnailSource::Default->value,
        //         'associations' => substr(
        //             $item,
        //             $offset,
        //             $length
        //         ),
        //     ];
        // }, $files);

        foreach ($files as $fileName) {
            $offset = strpos($fileName, '/') + 1;
            $length = strlen($fileName)
                - (strlen($fileName) - strrpos($fileName, '.')) - $offset;

            DB::table('thumbnails')->updateOrInsert(
                ['name' => $fileName, 'source' => ThumbnailSource::Default->value,],
                ['associations' => substr(
                    $fileName,
                    $offset,
                    $length
                ),]
            );
        }

        // $thumbnails = DB::table('thumbnails')->insert($data);
        // $thumbnails = DB::table('thumbnails')->upsert(
        //     $data,
        //     ['name'],
        //     ['source', 'associations']
        // );

        // dump($thumbnails);
    }
}
