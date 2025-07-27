<?php

namespace App\Orchid\Layouts\Bookmark;

use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class BookmarksListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'bookmarks';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', 'Id')->width('20px')->align(TD::ALIGN_CENTER)
                ->render(function ($bookmark) {
                    return Link::make($bookmark->id)
                        ->route('platform.bookmark', $bookmark->id);
                }),

            TD::make('user_id', 'User')->width('50px')->align(TD::ALIGN_CENTER)
                ->render(function ($bookmark) {
                    return Link::make($bookmark->user_id)
                        ->route('platform.systems.users.edit', $bookmark->user_id);
                }),

            TD::make('context_id', 'Context')->width('50px')->align(TD::ALIGN_CENTER)
                ->render(function ($bookmark) {
                    return Link::make($bookmark->context_id)
                        ->route('platform.context', $bookmark->context_id);
                }),

            TD::make('name', 'Name')->width('50px')->align(TD::ALIGN_CENTER),

            TD::make('link', 'Link')->width('50px')->align(TD::ALIGN_CENTER)
                ->render(function ($bookmark) {
                    return Link::make($bookmark->link)
                        ->href($bookmark->link)->target('_blank');
                }),

            TD::make('thumbnail_id', 'Thumbnail')->width('50px')->align(TD::ALIGN_CENTER)
                ->render(function ($bookmark) {
                    return Link::make($bookmark->thumbnail_id)
                        ->route('platform.thumbnail', $bookmark->thumbnail_id);
                }),

            TD::make('updated_at', 'Updated')->width('30px')->align(TD::ALIGN_CENTER),

            TD::make('Actions')->width('50px')->render(function ($context) {
                return DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([
                        Link::make('Open user')->route(
                            'platform.systems.users.edit',
                            $context->user_id
                        ),

                        Link::make('Open bookmark')->route(
                            'platform.context',
                            $context
                        ),
                    ]);
            }),
        ];
    }
}
