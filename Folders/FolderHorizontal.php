<?php

namespace App\View\Components\Folders;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FolderHorizontal extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public string $id,
        public string $name,
        public string $parentContextId,
        public string $order,
    ) {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.folders.folder-horizontal');
    }
}
