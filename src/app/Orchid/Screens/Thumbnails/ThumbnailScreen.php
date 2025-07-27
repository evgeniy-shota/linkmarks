<?php

namespace App\Orchid\Screens\Thumbnails;

use App\Models\Thumbnail;
use App\Orchid\Layouts\Thumbnail\ThumbnailInfo;
use Orchid\Screen\Screen;

class ThumbnailScreen extends Screen
{

    public $thumbnail;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Thumbnail $thumbnail): iterable
    {
        return [
            'thumbnail' => $thumbnail,
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Thumbnail';
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
            ThumbnailInfo::class,
        ];
    }
}
