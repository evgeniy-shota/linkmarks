<?php

namespace App\Console\Commands;

use App\Actions\ConvertImage;
use App\Jobs\ProcessThumbnail;
use App\Models\Thumbnail;
use App\Models\User;
use App\Services\ImageService;
use App\Services\ThumbnailService;
use DateTime;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;

class testFs extends Command
{
    public function __construct(private ImageService $imageService, private ThumbnailService $thServise)
    {
        parent::__construct();
    }
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-fs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        
    }
}
