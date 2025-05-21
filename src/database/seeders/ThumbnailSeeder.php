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
        $files = Storage::disk('public')->allFiles('/thumbnails-default');

        foreach ($files as $file) {
            $associations = substr($file, $start = strrpos($file, '/') + 1, strrpos($file, '.') - $start);

            Thumbnail::factory()->create([
                'name' => $file,
                'source' => 'default',
                'associations' => $associations,
            ]);
        }
    }
}
