<?php

namespace App\Console\Commands;

use App\Actions\ConvertImage;
use App\Jobs\ProcessThumbnail;
use App\Models\Thumbnail;
use App\Services\ImageService;
use App\Services\ThumbnailService;
use Illuminate\Console\Command;
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
        $domain = 'https://pingvinus.ru';
        // $response = Http::get($domain);
        // $body = $response->body();
        // Storage::disk('public')->put('pingvinus.txt', $body);

        $file = Storage::disk('public')->get('pingvinus.txt');
        $iamges = $this->getImagesFromPage($file);
        $imgForThumbnails = $this->getLogos($iamges);
        dump($imgForThumbnails);
        // dd('https://pingvinus.ru' . trim($imgForThumbnails[0], ' \t\n"'));
        $getImage = Http::accept('image/*')->get($domain . trim($imgForThumbnails[count($imgForThumbnails) - 1], ' \t\n"'));

        $img = $getImage->body();

        // $manager = ImageManager::imagick();
        // $image = $manager->read($img);
        // $encoded = $image->encode();

        $encoded = $this->imageService->scale($img);
        dump($encoded);
        $this->thServise->saveFromEncodedImage($encoded);

        Storage::disk('public')->put('pingvinus.png', $getImage->body());
    }

    protected function getImagesFromPage($page): array
    {
        $images = [];
        // $regx = '/\s?<img.+ \/>\s?/m';
        // $regx = '|<img\s.+\s\/>|m';
        $regx = '/"[^"]+((\.jpg)|(\.png)|(\.webp)|(\.icon)|(\.svg))"/m';
        $matches = preg_match_all($regx, $page, $images);
        return $images[0];
    }

    protected function getLogos(array $images): array
    {
        $regx = '/(logo)|(favicon)/m';
        $matches = preg_grep($regx, $images);
        return array_values($matches);
    }
}

    // "themes/pingvinus3/images/favicons/apple-touch-icon.png"
    // "themes/pingvinus3/images/favicons/apple-touch-icon-180x180.png"
    // "themes/pingvinus3/images/favicons/favicon-32x32.png"
    // "themes/pingvinus3/images/favicons/favicon-16x16.png"
    // "themes/pingvinus3/images/logo3.png"
    // "cr_images/modelImage/article/5352-teaser-9g4wz7r1tz.png"
    // "cr_images/modelImage/article/5346-teaser-97rh3ut653.png"
    // "cr_images/modelImage/article/5345-teaser-fagymc6idm.png"
    // "cr_images/userpicture/s/5344-0.png"
    // "cr_images/userpicture/s/5347-0.png"
    // "cr_images/modelImage/article/5343-teaser-9qrphxxelf.png"
    // "cr_images/userpicture/s/5341-0.png"
    // "cr_images/modelImage/article/5340-teaser-rqonp309uq.png"
    // "cr_images/modelImage/article/5342-teaser-63skm68d1n.png"
    // "cr_images/modelImage/article/5339-teaser-tt0u6rtd5m.png"
    // "cr_images/modelImage/article/5338-teaser-qbyu5ckday.png"
    // "cr_images/modelImage/article/5336-teaser-qmlzu4ed9v.png"
    // "cr_images/userpicture/s/5334-0.jpg"
    // "cr_images/modelImage/article/5335-teaser-d4gbzxkzfj.png"
    // "cr_images/userpicture/s/5333-0.png"
    // "cr_images/modelImage/article/5332-teaser-ywoe5rw86d.png"
    // "cr_images/userpicture/s/5330-0.png"
    // "cr_images/modelImage/article/5328-teaser-zbs0c7t9a0.png"
    // "cr_images/modelImage/article/5327-teaser-jc46ihx0b7.png"
    // "themes/pingvinus3/images/i/teleg48.png"
    // "themes/pingvinus3/images/i/youtube48.png"
    // "themes/pingvinus3/images/i/rss.png"
    // "cr_images/userpicture/s/5337-0.png"
    // "cr_images/userpicture/s/5344-0.png"
    // "cr_images/userpicture/s/5347-0.png"
    // "themes/pingvinus3/images/i/windows.png"
    // "themes/pingvinus3/images/i/tux.png"
    // "themes/pingvinus3/images/i/reward.png"
    // "themes/pingvinus3/images/logo-symbol-footer.png"
    // "themes/pingvinus3/images/i/teleg48.png"
    // "themes/pingvinus3/images/i/youtube48.png"
    // "themes/pingvinus3/images/i/rss.png"