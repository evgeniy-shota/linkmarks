@props(['text', 'active' => false, 'marked' => false])

<div class="relative box-border text-gray-300 hover:text-gray-100 transition duration-150 "
    x-bind:class="{ 'border-b-1 border-gray-300': {{ $active }}, 'border-b-1 border-sky-300': {{ $marked }} }">
    {{-- <x-html.icons.four-square /> --}}
    <x-html.icons.folder />
    <div x-text={{ $text }}></div>
    {{-- <div aria-label="info"
        class="absolute top-0 right-0 w-[8px] h-[8px] bg-sky-400 rounded-sm">
    </div> --}}
</div>
