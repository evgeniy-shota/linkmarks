<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class FilterByTags extends AbstractFilter
{
    public const TAGS_INCLUDED = 'tagsIncluded';
    public const TAGS_EXCLUDED = 'tagsExcluded';

    public function getCallback(): array
    {
        return [
            self::TAGS_INCLUDED => [$this, 'tagsIncluded'],
            self::TAGS_EXCLUDED => [$this, 'tagsExcluded'],
        ];
    }

    public function tagsIncluded(Builder $builder, $value)
    {
        $builder->whereIn('id', $value);
    }

    public function tagsExcluded(Builder $builder, $value)
    {
        $builder->whereNotIn('id', $value);
    }
}
