<?php

namespace App\Orchid\Layouts\Bookmark;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class BookmarkInfo extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title = "Bookmark";

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): iterable
    {
        return [
            Input::make('bookmark.id')->title('Id')->required()
                ->type('number'),

            Input::make('bookmark.context_id')->title('Context')->required()
                ->type('number'),

            Input::make('bookmark.link')->title('Link')->required()
                ->type('url'),

            Input::make('bookmark.name')->title('Name')->required()
                ->type('text'),

            Input::make('bookmark.thumbnail_id')->title('Thumbnail id')->required()
                ->type('number'),

            Input::make('bookmark.updated_at')->title('Updated')->required()
                ->type('text'),

            Input::make('bookmark.order')->canSee(false),
        ];
    }
}
