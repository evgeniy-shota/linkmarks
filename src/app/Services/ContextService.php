<?php

namespace App\Services;

use App\Actions\GetLastOrderInContext;
use App\Models\Context;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Request;

class ContextService
{
    public function getRootContext(int $userId): ?Context
    {
        $context = Context::where('user_id', $userId)->where('is_root', true)->first();
        return $context;
    }

    public function getContext(int $id, bool $withTags = true): ?Context
    {
        $query = Context::query();

        if ($withTags) {
            $query->with('tags:id,name,description');
        }

        return $query->find($id);
        return Context::with('tags:id,name,description')->find($id);
    }

    public function getContexts(int $idCurrentContext): Builder
    {
        $context = Context::with('tags:id,name,description')
            ->where('parent_context_id', $idCurrentContext)->orderBy('order');
        return $context;
    }

    public function createContext(array $data, int $userId): Context
    {
        $data['user_id'] = $userId;
        $context = Context::create($data);
        return $context;
    }

    public function updateContext(array $data, int $id): ?Context
    {
        $context = Context::find($id);

        if (!isset($data['order']) || $context->parent_context_id != $data['parent_context_id']) {
            $maxOrder = GetLastOrderInContext::getOrder($data['parent_context_id']);
            $data['order'] = $maxOrder + 1;
        }

        $context->update($data);
        return $context;
    }

    public function deleteContext(int $id): bool
    {
        $context = Context::find($id);

        if ($context && !$context->is_root) {
            $context->delete();
            return true;
        }

        return false;
    }
}
