<?php

namespace App\Orchid\Screens\Bookmarks;

use App\Models\Bookmark;
use App\Orchid\Layouts\Bookmark\BookmarkInfo;
use App\Services\BookmarkService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Toast;

class BookmarkScreen extends Screen
{

    public function __construct(protected BookmarkService $bookmarkService) {}

    public $bookmark;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Bookmark $bookmark): iterable
    {
        return [
            'bookmark' => $bookmark
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Bookmark';
    }

    /**
     * The screen's action buttons.
     *
     * @return \Orchid\Screen\Action[]
     */
    public function commandBar(): iterable
    {
        return [
            Button::make(__('Remove'))
                ->icon('bs.trash3')
                ->confirm(__('The bookmark will be deleted without the possibility of recovery.'))
                ->method('remove'),

            Button::make(__('Save'))
                ->icon('bs.check-circle')
                ->method('save'),
        ];
    }

    /**
     * The screen's layout elements.
     *
     * @return \Orchid\Screen\Layout[]|string[]
     */
    public function layout(): iterable
    {
        return [
            BookmarkInfo::class,
        ];
    }

    public function save(Bookmark $bookmark, Request $request)
    {
        $validator = Validator::make(
            $request->get('bookmark'),
            [
                'context_id' => ['nullable', 'numeric', 'integer'],
                'link' => ['required', 'url', 'max:400'],
                'name' => ['nullable', 'string', 'max:150'],
                'thumbnail_id' => ['nullable', 'numeric', 'integer'],
                'order' => ['nullable', 'numeric', 'integer'],
            ]
        );

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $data = $validator->validated();

        $this->bookmarkService->updateBookmark($bookmark->id, $data, null);

        Toast::info(__('Bookmark was saved'));
    }

    public function remove(Bookmark $bookmark)
    {
        $bookmark->delete();

        Toast::info(__('Bookmark was removed'));
        return redirect()->route('platform.bookmarks');
    }
}
