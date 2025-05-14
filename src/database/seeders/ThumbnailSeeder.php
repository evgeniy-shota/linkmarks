<?php

namespace Database\Seeders;

use App\Models\Thumbnail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ThumbnailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $files = Storage::disk('public')->allDirectories('/thumbnails');
        dd($files);
        Thumbnail::factory()->count(5)->create();
    }
}
