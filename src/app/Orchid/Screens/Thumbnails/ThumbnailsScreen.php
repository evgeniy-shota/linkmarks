<?php

namespace App\Orchid\Screens\Thumbnails;

use App\Models\Thumbnail;
use App\Orchid\Layouts\Thumbnail\ThumbnailsListLayout;
use Orchid\Screen\Screen;
use Orchid\Screen\TD;

class ThumbnailsScreen extends Screen
{
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(): iterable
    {
        return [
            'thumbnails' => Thumbnail::all(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Thumbnails';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            ThumbnailsListLayout::class,
        ];
    }
}
