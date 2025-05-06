<?php

namespace Database\Seeders;

use App\Models\Thumbnail;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ThumbnailSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Thumbnail::factory()->count(5)->create();
    }
}
