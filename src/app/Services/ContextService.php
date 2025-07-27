<?php

namespace App\Services;

use App\Actions\GetLastOrderInContext;
use App\Http\Filters\FilterByTags;
use App\Models\Context;
use App\Models\User;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Request;

class ContextService
{
    public function search(string $searchRequest, int $userId)
    {
        $contexts = Context::search($searchRequest)->query(
            function ($builder) {
                $builder->with('tags:id,name,description');
            }
        )->where('user_id', $userId)->get();

        return $contexts;
    }

    public function getRootContext(int $userId): ?Context
    {
        $context = Context::where('user_id', $userId)->where('is_root', true)
            ->first();
        return $context;
    }

    public function getContext(int $id, bool $withTags = true): ?Context
    {
        $query = Context::query();

        if ($withTags) {
            $query->with('tags:id,name,description');
        }

        return $query->find($id);
    }

    public function getContexts(int $idCurrentContext): Builder
    {
        $context = Context::with('tags:id,name,description')
            ->where('parent_context_id', $idCurrentContext)->orderBy('order');
        return $context;
    }

    /**
     * @return Builder
     */
    public function getAllContexts(
        int $userId,
        bool $excludeRoot = false,
        string $orderBy = "order"
    ): Builder {
        $context = Context::with('tags:id,name,description')
            ->where('user_id', $userId)
            ->when(
                $excludeRoot,
                function (Builder $query) {
                    $query->where('is_root', false);
                }
            )->orderBy('is_root', 'desc')->orderBy($orderBy, 'asc');
        return $context;
    }

    /**
     * 
     */
    public function getFilteredContexts(
        int $id,
        int $userId,
        bool $contextualFiltration,
        bool $discardToContexts,
        array $filterParams
    ) {
        if (!$contextualFiltration && count($filterParams) > 0) {
            $contexts = $this->getAllContexts($userId, true);

            if (!$discardToContexts) {
                $contextFilter = app()->make(
                    FilterByTags::class,
                    [
                        'queryParams' => $filterParams,
                        'tableName' => 'contexts_tags'
                    ],
                );
                $contexts = $contexts->filter($contextFilter);
            }

            $contexts = $contexts->get();
            return $contexts;
        } else {
            $contexts = $this->getContexts($id);

            if (count($filterParams) > 0) {
                if (!$discardToContexts) {
                    $contextFilter = app()->make(
                        FilterByTags::class,
                        [
                            'queryParams' => $filterParams,
                            'tableName' => 'contexts_tags'
                        ],
                    );
                    $contexts = $contexts->filter($contextFilter);
                }
            }

            $contexts = $contexts->get();
            return $contexts;
        }
    }

    public function createContext(array $data, int $userId): Context
    {
        $data['user_id'] = $userId;

        if (!isset($data['order'])) {
            $data['order'] =
                GetLastOrderInContext::getOrder($data['parent_context_id']);
        }

        $data['order'] += 1;
        $context = Context::create($data);

        if (isset($data['tags'])) {
            $context->tags()->attach($data['tags']);
        }

        return $context;
    }

    public function updateContext(
        array $data,
        int $id,
        ?array $tags
    ): ?Context {
        $context = Context::find($id);

        if (
            !isset($data['order'])
            || $context->parent_context_id != $data['parent_context_id']
        ) {
            $maxOrder = GetLastOrderInContext::getOrder(
                $data['parent_context_id']
            );
            $data['order'] = $maxOrder + 1;
        }

        $context->update($data);
        $context->tags()->detach();

        if (isset($tags)) {
            $context->tags()->attach($tags);
        }

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
