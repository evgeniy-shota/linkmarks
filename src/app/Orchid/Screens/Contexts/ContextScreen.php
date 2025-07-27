<?php

namespace App\Orchid\Screens\Contexts;

use App\Models\Bookmark;
use App\Models\Context;
use App\Orchid\Layouts\Bookmark\BookmarksListLayout;
use App\Orchid\Layouts\Context\ContextInfo;
use App\Orchid\Layouts\Context\ContextsListLayout;
use App\Services\ContextService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Orchid\Screen\Actions\Button;
use Orchid\Screen\Screen;
use Orchid\Support\Facades\Layout;
use Orchid\Support\Facades\Toast;

class ContextScreen extends Screen
{
    public function __construct(protected ContextService $contextService) {}

    public $context;
    /**
     * Fetch data to be displayed on the screen.
     *
     * @return array
     */
    public function query(Context $context): iterable
    {
        return [
            'context' => $context,
            'contexts' =>
            Context::where('parent_context_id', $context->id)->get(),
            'bookmarks' => Bookmark::where('context_id', $context->id)->get(),
        ];
    }

    /**
     * The name of the screen displayed in the header.
     *
     * @return string|null
     */
    public function name(): ?string
    {
        return 'Context';
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
                ->confirm(__('The context and its children will be deleted without the possibility of recovery.'))
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
            Layout::block(ContextInfo::class)->title('Context info'),

            Layout::split([
                ContextsListLayout::class,
                BookmarksListLayout::class,
            ])->ratio('50/50'),
        ];
    }

    public function save(Context $context, Request $request)
    {
        $validator = Validator::make($request->get('context'), [
            'name' => 'required|string|max:150',
            'parent_context_id' => 'nullable|numeric|integer',
            'order' => 'nullable|numeric|integer',
        ]);

        $data = $validator->validated();

        $this->contextService->updateContext($data, $context->id, null);

        Toast::info(__('Context was saved'));
    }

    public function remove(Context $context)
    {
        $context->delete();

        Toast::info(__('Context was removed'));
        return redirect()->route('platform.contexts');
    }
}
