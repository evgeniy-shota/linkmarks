<?php

namespace App\Services;

use Exception;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Http;

class HttpQueryService
{
    const HEADERS = [
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/88.0.4324.104 Safari/537.36',
        'Accept-Encoding' => 'gzip, deflate, br',
    ];
    const DEFAULT_ACCEPT_TYPE = 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9';
    const IMAGE_ACCEPT_TYPE = 'image/*';

    public function getPage(
        string $url,
        string $acceptType = self::DEFAULT_ACCEPT_TYPE,
        array $headers = self::HEADERS
    ): ?string {
        try {
            $response = Http::withHeaders($headers)->accept($acceptType)->get($url);
        } catch (ConnectionException $connectionEx) {

            return null;
        } catch (Exception $exception) {
            return null;
        }

        if ($response->successful()) {
            $contentType = $response->header('Content-Type');
            $responseBody = $response->body();

            if (str_contains($contentType, 'windows-1251')) {
                return mb_convert_encoding($responseBody, 'UTF-8', 'WINDOWS-1251');
            }

            return $responseBody;
        }

        return null;
    }

    public function getImage(
        string $url,
        string $acceptType = self::IMAGE_ACCEPT_TYPE
    ): ?string {
        return $this->getPage($url, $acceptType);
    }

    public function pool(
        array $urls,
        string $acceptType = self::DEFAULT_ACCEPT_TYPE,
        array $headers = self::HEADERS
    ): array {
        $responses = Http::pool(
            fn(Pool $pool) =>
            array_map(
                fn($item) =>
                $pool->withHeaders($headers)->accept($acceptType)->get($item),
                $urls
            )
        );

        return $responses;
    }

    public function poolImages(
        array $urls,
        string $acceptType = self::IMAGE_ACCEPT_TYPE,
        array $headers = self::HEADERS
    ): array {
        $images = $this->pool($urls, $acceptType, $headers);
        return array_map(function ($item) {
            return $item->body();
        }, $images);
    }
}
