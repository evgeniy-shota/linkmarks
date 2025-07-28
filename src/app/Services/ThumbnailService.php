<?php

namespace App\Services;

use App\Enums\ThumbnailSource;
use App\Jobs\ProcessThumbnail;
use App\Models\Thumbnail;
use Illuminate\Container\Attributes\Storage as AttributesStorage;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\LazyCollection;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Imagick\Driver;
use Illuminate\Support\Str;
use Intervention\Image\EncodedImage;
use Intervention\Image\Interfaces\EncodedImageInterface;
use Nette\Utils\Arrays;

class ThumbnailService
{
    public function generateUrl(string|Thumbnail $data): string
    {
        if (is_string($data)) {
            return Storage::url($data);
        }

        return Storage::url($data->name);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<\App\Models\Thumbnail>|null
     */
    public function getByAssociations(string $associations, $userId): ?Collection
    {
        return Thumbnail::where(function (Builder $query) use ($userId) {
            $query->where('user_id', $userId)
                ->orWhere('source', ThumbnailSource::Default->value)
                ->orWhere('source', ThumbnailSource::Gstatic->value);
        })->where('associations', $associations)->orderBy('id')->get();
    }

    public function getDefault(bool $idOnly = true): Thumbnail|int
    {
        $defaultThumbnail = Thumbnail::where('source', 'default')->where('associations', 'default');

        if ($idOnly) {
            $defaultThumbnail = $defaultThumbnail->select('id');
        }

        return $defaultThumbnail->first();
    }

    public function getLazyAll(?int $userId): LazyCollection
    {
        return Thumbnail::when(
            isset($userId),
            function (Builder $query) use ($userId) {
                $query->where('user_id', $userId);
            }
        )->lazy();
    }

    public function getById($id)
    {
        return Thumbnail::find($id);
    }

    public function getThumbnailsIn(array $ids, array $selectCol = []): Collection
    {
        $thumbnails = Thumbnail::whereIn('id', $ids);

        if (count($selectCol) > 0) {
            $thumbnails = $thumbnails->select($selectCol);
        }

        $thumbnails = $thumbnails->get();
        return $thumbnails;
    }

    public function create(
        string $file,
        $user_id,
        $source = ThumbnailSource::Default->value,
        string $associations = '',
    ): ?Thumbnail {

        $thumbnail = Thumbnail::create([
            'user_id' => $user_id,
            'name' => $file,
            'source' => $source,
            'associations' => $associations,
        ]);

        return $thumbnail;
    }

    public function massCreate(
        array $images,
        $user_id,
        $source = ThumbnailSource::Default->value,
        string $associations = ''
    ) {
        $thumbnails = [];

        for ($i = 0, $size = count($images); $i < $size; $i++) {
            $thumbnails[] = $this->create(
                $images[$i],
                $user_id,
                $source,
                $associations
            );
        }

        return $thumbnails;
    }

    public function massCreateIfNotExist(
        array $images,
        $userId,
        $source = ThumbnailSource::Default->value,
        string $associations = ''
    ) {
        $thumbnails = [];

        for ($i = 0, $size = count($images); $i < $size; $i++) {

            if ($thumbnail = Thumbnail::where('user_id', $userId)
                ->where('name', $images[$i])->first()
            ) {
                $thumbnails[] = $thumbnail;
                continue;
            }

            $thumbnails[] = $this->create(
                $images[$i],
                $userId,
                $source,
                $associations
            );
        }

        return $thumbnails;
    }

    public function update(string $id, array $data)
    {
        $thumbnail = Thumbnail::where('id', $id)->update($data);
        return $thumbnail;
    }
}
