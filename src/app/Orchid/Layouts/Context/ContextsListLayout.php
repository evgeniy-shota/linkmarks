<?php

namespace App\Orchid\Layouts\Context;

use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ContextsListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'contexts';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', 'Id')->align('center')->width('20px')
                ->render(function ($context) {
                    return Link::make($context->id)
                        ->route('platform.context', $context->id);
                }),

            TD::make('user_id', 'User id')->align('center')->width('30px')
                ->render(function ($context) {
                    return Link::make($context->user_id)
                        ->route('platform.systems.users.edit', $context->user_id);
                }),

            TD::make('name', 'Name')->align('center')->width('100x'),

            TD::make('is_root', 'Is root')
                ->align('center')
                ->width('50x')
                ->render(function ($context) {
                    return $context->is_root === true ? 'yes' : 'no';
                }),

            TD::make('parent_context_id', 'Parent context id')
                ->align('center')
                ->width('100x'),

            TD::make('Actions')->width('50px')->render(function ($context) {
                return DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([
                        Link::make('Open user')->route(
                            'platform.systems.users.edit',
                            $context->user_id
                        ),

                        Link::make('Open context')->route(
                            'platform.context',
                            $context
                        ),
                    ]);
            }),
        ];
    }
}
