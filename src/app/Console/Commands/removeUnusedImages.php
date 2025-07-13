<?php

namespace App\Console\Commands;

use App\Enums\ThumbnailSource;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class removeUnusedImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:remove-unused-images {--days=30}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes unused thumbnails and related files. --days=30';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $days = $this->option('days') ?? 30;
        $date = now()->subDays($days > 0 ? $days : 30);
        $unusedThumbnails = DB::table('thumbnails as t')
            ->leftJoin('bookmarks as b', 't.id', '=', 'b.thumbnail_id')
            ->select('t.id as id', 't.name as file')
            ->where('t.source', '!=', ThumbnailSource::Default->value)
            ->where('t.updated_at', '<', $date)
            ->where('b.id', null)
            ->get();

        $thumbnailsToDelete = [];
        $filesToDelete = [];

        foreach ($unusedThumbnails as $thumbnail) {
            $thumbnailsToDelete[] = $thumbnail->id;

            if (Storage::disk('public')->exists($thumbnail->file)) {
                $filesToDelete[] = $thumbnail->file;
            }
        }

        if (count($thumbnailsToDelete) > 0) {
            DB::table('thumbnails')->whereIn('id', $thumbnailsToDelete)->delete();
        }

        if (count($filesToDelete)) {
            Storage::disk('public')->delete($filesToDelete);
        }
    }
}
