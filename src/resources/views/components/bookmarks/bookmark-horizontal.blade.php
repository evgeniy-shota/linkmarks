@props([
    'id',
    'name',
    'link',
    'thumbnail',
    'elementAttribute' => 'data-element',
    'elementAttributeAction' => 'data-element-action',
    'elementAttributeType' => 'data-element-type',
    'tags' => [],
])

{{-- <div x-data
    class="relative inline-block bg-gray-600 text-gray-100 hover:bg-gray-500 hover:text-gray-50 box-border rounded-md w-full px-1 py-1 transition duration-150"> --}}

<x-horizontal-container>
    <div x-bind:id="'boormarkContainer-' + {{ $id }}"
        x-bind:{{ $elementAttribute }}='{{ $link }}'
        {{ $elementAttributeAction }}='open-new-tab'
        {{ $elementAttributeType }}="bookmark" class="h-20 flex">
        <div class="w-1/4 flex-none overflow-hidden me-1">

            <div class="absolute top-0 left-0">
                <x-html.tooltip direction='right' tip='open in current tab'>
                    <div x-bind:{{ $elementAttribute }}='{{ $link }}'
                        {{ $elementAttributeAction }}='open-current-tab'
                        class="btn btn-sm bg-gray-600 border-0 text-gray-100 shadow-none opacity-20 hover:opacity-100 px-2 transition duration-150">
                        {{-- <img src="img/icons/box-arrow-in-down-left.svg"> --}}
                        <x-html.icons.box-arrow-down />
                    </div>
                </x-html.tooltip>
                {{-- <div data-bookmark='{{ $link }}' data-bookmark-action='copy'
                    class="btn btn-sm bg-gray-600 border-gray-600 text-gray-100 shadow-md opacity-50 hover:opacity-100 px-2">
                    <img src="img/icons/copy.svg">
                </div> --}}
            </div>

            <div class="flex justify-center items-center h-full">
                <picture>
                    <img x-bind:src="{{ $thumbnail }}" alt=""
                        class="flex-none">
                </picture>
            </div>
        </div>

        <div class="w-3/4 flex-none overflow-hidden">
            <div class="grid h-full grid-cols-1 content-between">

                {{-- name --}}
                <div class="truncate text-lg/6 font-medium py-1 pe-2"
                    x-text="{{ $name }}">
                </div>

                {{-- tags --}}
                <div class="flex justify-start items-center gap-1">
                    <template x-for="item in {{ $tags }}">
                        <x-html.tags.tag xText="item.short_name" />
                    </template>
                </div>

                {{-- link and edit --}}
                <div class="w-7/8">
                    <div class="w-full">
                        <x-html.tooltip direction='top' tip='click to copy link'
                            class="w-full">
                            <small x-text="{{ $link }}"
                                x-bind:{{ $elementAttribute }}='{{ $link }}'
                                {{ $elementAttributeAction }}='copy'
                                class="flex-none inline-block max-w-full px-1 rounded-sm text-gray-100 truncate cursor-pointer hover:bg-gray-600 transition duration-150">

                            </small>
                        </x-html.tooltip>
                    </div>

                    <div class="absolute bottom-0 right-0">
                        <x-html.tooltip direction='left' tip='Edit'
                            class="flex-none">
                            <div x-bind:{{ $elementAttribute }}='{{ $id }}'
                                {{ $elementAttributeAction }}='edit'
                                class="flex-none btn btn-sm bg-gray-600 opacity-20 hover:opacity-100 border-0 text-gray-100 shadow-none px-2 transition duration-150">
                                {{-- <img src="img/icons/three-dots-vertical.svg"> --}}
                                <x-html.icons.three-dots />
                            </div>
                        </x-html.tooltip>
                    </div>
                    {{-- <div class="tooltip tooltip-left">
                        <div class="tooltip-content bg-gray-600"></div>

                    </div> --}}
                </div>
            </div>
        </div>
    </div>
</x-horizontal-container>
{{-- </div> --}}
