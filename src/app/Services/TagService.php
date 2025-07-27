<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tag;

class TagService
{
    public function createTag(array $data, int $userId)
    {
        $data['user_id'] = $userId;
        return Tag::create($data);
    }
}
