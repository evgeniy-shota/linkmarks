<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class BookmarkResource extends JsonResource
{
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
            'thumbnail' => $this->thumbnail,
            // 'thumbnail' => Storage::url($this->thumbnail),
            'thumbnail_id' => $this->thumbnail_id,
            'tags' => new TagCollection($this->tags),
            // 'is_enabled' => $this->is_enabled,
            'order' => $this->order,
        ];
    }
}
