<?php

namespace App\Services;

class HtmlParseService
{
    const LOGO_REG = '/logo|Logo|favicon/';

    public function getImages(
        string $page,
        string $domain,
        string $imageNameReg = self::LOGO_REG
    ) {
        $images = [];
        $res = [];
        $imageRegx = '/"[^"]+((\.jpg)|(\.png)|(\.webp)|(\.icon)|(\.svg))"/m';
        $imageSizeRegx = '/[0-9]{2,3}/';
        $matches = preg_match_all($imageRegx, $page, $images);

        if ($matches === 0 || $matches === false) {
            return [];
        }

        $size = count($images[0]) - 1;

        for ($i = 0; $i < $size; $i += 1) {

            if (preg_match($imageNameReg, $images[0][$i])) {
                $image = trim($images[0][$i], '\n\t"');
                $parsedImageUrl = parse_url($image);

                $path = isset($parsedImageUrl['scheme'])
                    && isset($parsedImageUrl['host'])
                    ? $image
                    : $domain .
                    ($domain[-1] != '/' && $image[0] != '/' ? '/' : '') .
                    $image;

                $startIndex = strrpos($image, '/') + 1;
                $endIndex = strrpos($image, '.');
                $name = substr($image,  $startIndex, $endIndex - $startIndex);

                $imageSize = [];
                preg_match($imageSizeRegx, $name, $imageSize);

                $res[] = [
                    'path' => $path,
                    'name' => $name,
                    'size' => isset($imageSize[0]) ? (int)$imageSize[0] : 0,
                ];
            }
        }

        return $res;
    }

    function getLogos(array $images): array
    {
        $regx = '/logo|favicon/';
        $logos = [];

        for ($i = 0, $size = count($images); $i < $size; $i++) {
            if (preg_match($regx, $images[$i])) {
                $logos[] = $images[$i];
            }
        }

        return $logos;
    }

    public function getTitle(string $page)
    {
        $titleStr = [];
        preg_match('/<title[\s\w="\']*>/', $page, $titleStr);

        if (count($titleStr) == 0) {
            return null;
        }

        $titleStart = stripos($page, $titleStr[0]);
        $titleEnd = stripos($page, '</title>');
        $titleLength = $titleEnd - $titleStart;

        $title = trim(strip_tags(substr($page, $titleStart + strlen($titleStr[0]), $titleLength)));
        return strlen($title) == 0 ? null : $title;
    }
}
