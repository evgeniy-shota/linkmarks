<?php

namespace App\Orchid\Layouts\Thumbnail;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class ThumbnailInfo extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title;

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): iterable
    {
        return [
            Input::make('thumbnail.id')->title('Id')->required()->type('number'),

            Input::make('thumbnail.user_id')->title('User')->required()->type('number'),

            Input::make('thumbnail.name')->title('Name')->required()->type('text'),

            Input::make('thumbnail.source')->title('Source')->required()->type('text'),

            Input::make('thumbnail.associations')->title('Associations')->required()->type('text'),

            CheckBox::make('thumbnail.is_processed')->value('thumbnail.is_processed')->title('Is processed')
                ->help('Attention! Edit careful!'),

                CheckBox::make('thumbnail.is_enabled')->value('thumbnail.is_enabled')->title('Is enabled')
                ->help('Attention! Edit careful!'),

        ];
    }
}
