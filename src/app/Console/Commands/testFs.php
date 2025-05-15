<?php

namespace App\Console\Commands;

use App\Actions\ConvertImage;
use App\Jobs\ProcessThumbnail;
use App\Models\Thumbnail;
use App\Services\ImageService;
use App\Services\ThumbnailService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

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
        $thumbnail = Thumbnail::where('name', 'like', "%.ico")->first();
        // dd($thumbnail);
        $process = new ProcessThumbnail($thumbnail);
        $process->handle($this->imageService, $this->thServise);
        // $this->process->handle($thumbnail);
    }
}
