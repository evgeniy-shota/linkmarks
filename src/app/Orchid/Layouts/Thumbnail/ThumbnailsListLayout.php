<?php

namespace App\Orchid\Layouts\Thumbnail;

use Orchid\Screen\Actions\DropDown;
use Orchid\Screen\Actions\Link;
use Orchid\Screen\Layouts\Table;
use Orchid\Screen\TD;

class ThumbnailsListLayout extends Table
{
    /**
     * Data source.
     *
     * The name of the key to fetch it from the query.
     * The results of which will be elements of the table.
     *
     * @var string
     */
    protected $target = 'thumbnails';

    /**
     * Get the table cells to be displayed.
     *
     * @return TD[]
     */
    protected function columns(): iterable
    {
        return [
            TD::make('id', 'Id')->width('20px')->align(TD::ALIGN_CENTER)
                ->render(function ($thumbnail) {
                    return Link::make($thumbnail->id)
                        ->route('platform.thumbnail', $thumbnail->id);
                }),

            TD::make('name', 'Name')->width('70px')->align(TD::ALIGN_CENTER),


            TD::make('user_id', 'User')->width('30px')->align(TD::ALIGN_CENTER)
                ->render(function ($thumbnail) {
                    return isset($thumbnail->user_id) ?
                        Link::make($thumbnail->user_id)
                        ->route('platform.systems.users.edit', $thumbnail->user_id)
                        : 'null';
                }),

            TD::make('source', 'source')->width('50px')->align(TD::ALIGN_CENTER),

            TD::make('associations', 'associations')->width('50px')->align(TD::ALIGN_CENTER),

            TD::make('Actions')->width('50px')->render(function ($thumbnail) {
                return DropDown::make()
                    ->icon('bs.three-dots-vertical')
                    ->list([
                        isset($thumbnail->user_id)
                            ? Link::make('Open user')->route(
                                'platform.systems.users.edit',
                                $thumbnail->user_id
                            )
                            : Link::make('Create user')->route(
                                'platform.systems.users'
                            ),
                    ]);
            }),
        ];
    }
}
