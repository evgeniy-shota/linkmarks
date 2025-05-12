<?php

namespace App\Services;

use App\Models\Context;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Request;

class ContextService
{
    public function getRootContext(int $userId): ?Context
    {
        $context = Context::where('user_id', $userId)->where('is_root', true)->first();
        return $context;
    }

    public function getContext(int $id): ?Context
    {
        return Context::find($id);
    }

    public function getContexts(int $idCurrentContext): Collection|Context|null
    {
        $context = Context::where('parent_context_id', $idCurrentContext)->orderBy('order')->get();
        return $context;
    }

    public function createContext(array $data, int $userId): Context
    {
        $data['user_id'] = $userId;
        $context = Context::create($data);
        return $context;
    }

    public function updateContext(array $data, int $id): bool
    {
        $context = Context::update(['id' => $id], $data);
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
