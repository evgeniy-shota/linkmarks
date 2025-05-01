<?php

namespace App\View\Components\Bookmarks;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BookmarkHorizontal extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $name,
        public string $link,
        public string $description,
        public string $logo
    ) {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.bookmarks.bookmark-horizontal');
    }
}
