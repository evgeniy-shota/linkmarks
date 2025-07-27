<?php

namespace App\Orchid\Layouts\Context;

use Orchid\Screen\Field;
use Orchid\Screen\Fields\CheckBox;
use Orchid\Screen\Fields\Input;
use Orchid\Screen\Layouts\Rows;

class ContextInfo extends Rows
{
    /**
     * Used to create the title of a group of form elements.
     *
     * @var string|null
     */
    protected $title = 'Context info';

    /**
     * Get the fields elements to be displayed.
     *
     * @return Field[]
     */
    protected function fields(): iterable
    {
        return [
            Input::make('context.name')->title('Name')->required()->type('text'),

            CheckBox::make('context.is_root')->value('context.is_root')->title('Is root')
                ->help('Attention! Edit careful!'),

            Input::make('context.user_id')->title('User id')->required()
                ->type('number'),

            Input::make('context.parent_context_id')->title('Parent context id')
                ->type('number'),

            Input::make('context.order')->canSee(false),
        ];
    }
}
