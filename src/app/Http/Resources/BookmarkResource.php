<?php

namespace App\Http\Resources;

use App\Models\Bookmark;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

/**
 * @mixin \App\Models\Bookmark
 */
class BookmarkResource extends JsonResource
{
    protected array $extendData = [];

    public function __construct(Bookmark $bookmark, array $extendData = [])
    {
        parent::__construct($bookmark);
        $this->extendData = $extendData;
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'context_id' => $this->context_id,
            'link' => $this->link,
            'name' => $this->name,
            'thumbnail' => $this->when(
                isset($this->extendData['thumbnailName']),
                fn() => $this->extendData['thumbnailName'],
                fn() => $this->thumbnail,
            ),
            'thumbnail_id' => $this->thumbnail_id,
            'tags' => TagResource::collection($this->tags),
            'order' => $this->order,
        ];
    }
}
