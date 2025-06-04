<?php

namespace App\Services;

class HtmlParseService
{
    public function getImages(string $page)
    {
        $images = [];
        // $regx = '/\s?<img.+ \/>\s?/m';
        // $regx = '|<img\s.+\s\/>|m';
        $regx = '/"[^"]+((\.jpg)|(\.png)|(\.webp)|(\.icon)|(\.svg))"/m';
        $matches = preg_match_all($regx, $page, $images);
        return array_map(function ($item) {
            return trim($item, "\"");
        }, $images[0]);
    }

    public function getTitle(string $page) {}
}
