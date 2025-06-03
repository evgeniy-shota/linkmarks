<?php

namespace App\Models\Traits;

use App\Http\Filters\FilterInterface;
use Illuminate\Contracts\Database\Eloquent\Builder;

trait Filterable
{
    public function scopeFilter(Builder $builder, FilterInterface $filter)
    {
        $filter->apply($builder);

        return $builder;
    }
}
