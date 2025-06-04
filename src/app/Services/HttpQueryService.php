<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class HttpQueryService
{
    const DEFAULT_ACCEPT_TYPE = 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7';

    public function getPage(string $url, string $acceptType = self::DEFAULT_ACCEPT_TYPE): ?string
    {
        $response = Http::accept($acceptType)->get($url);

        if ($response->successful()) {
            return $response->body();
        }

        return null;
    }

    public function getImage(string $url): ?string
    {
        $acceptType = 'image/*';
        return $this->getPage($url, $acceptType);
    }
}
