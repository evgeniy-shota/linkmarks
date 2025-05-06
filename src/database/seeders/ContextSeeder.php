<?php

namespace Database\Seeders;

use App\Models\Context;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ContextSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Context::factory()->count(50)->create();
    }
}
