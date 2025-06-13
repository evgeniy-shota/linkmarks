<?php

namespace App\Actions;

use App\Services\HttpQueryService;

class GetFaviconFromGstatic
{
    const GSTATIC_URL = "https://t2.gstatic.com/faviconV2?client=SOCIAL&type=FAVICON&fallback_opts=TYPE,SIZE,URL";

    public function __construct(private HttpQueryService $httpQuery) {}

    public function getFavicon(string $url, int $size = 96)
    {
        $requestParams = "&url=$url&size=$size";
        $response = $this->httpQuery->getPage(self::GSTATIC_URL . $requestParams);
        return $response;
    }
}
