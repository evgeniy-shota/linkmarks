@php
    $bookmarkId = 'bookmarkContainer' . $id;
    // $onClickAction = $onClick . '($event,\'' . $link . "')";
@endphp

<div x-data
    class="relative inline-block bg-gray-600 text-gray-100 hover:bg-gray-500 hover:text-gray-50 box-border rounded-md w-full px-1 py-1 my-1 transition duration-150">

    <div id="{{ $bookmarkId }}" class="h-20 flex" data-bookmark='{{ $link }}' data-bookmark-action='open-new-tab'>
        <div class="w-1/4 flex-none overflow-hidden">

            <div class="absolute top-0 left-0">
                <x-html.tooltip direction='right' tip='open in current tab'>
                    <div data-bookmark='{{ $link }}' data-bookmark-action='open-current-tab'
                        class="btn btn-sm bg-gray-500 hover:bg-gray-600 border-0 text-gray-100 shadow-none opacity-50 hover:opacity-100 px-2">
                        <img src="img/icons/box-arrow-in-down-left.svg">
                    </div>
                </x-html.tooltip>
                {{-- <div data-bookmark='{{ $link }}' data-bookmark-action='copy'
                    class="btn btn-sm bg-gray-600 border-gray-600 text-gray-100 shadow-md opacity-50 hover:opacity-100 px-2">
                    <img src="img/icons/copy.svg">
                </div> --}}
            </div>

            <div class="flex justify-center items-center h-full">
                <picture>
                    <img src="{{ $thumbnail }}" alt="" class="flex-none">
                </picture>
            </div>
        </div>

        <div class="w-3/4 flex-none overflow-hidden">
            <div class="grid h-full grid-cols-1 content-between">
                <div class="truncate text-lg ">{{ $name }} </div>
                <div class="flex justify-between items-center">
                    <div>
                        <x-html.tooltip direction='top' tip='click to copy link'>
                            <small data-bookmark='{{ $link }}' data-bookmark-action='copy'
                                class="px-1 rounded-sm bg-gray-500 text-gray-100 cursor-pointer hover:bg-gray-600 transition duration-150">
                                {{ $link }}
                            </small>
                        </x-html.tooltip>
                    </div>

                    <x-html.tooltip direction='left' tip='Edit'>
                        <div data-bookmark='{{ $id }}' data-bookmark-action='edit'
                            class="btn btn-sm bg-gray-500 hover:bg-gray-600 border-0 text-gray-100 shadow-none px-2">
                            <img src="img/icons/three-dots-vertical.svg">
                        </div>
                    </x-html.tooltip>

                    {{-- <div class="tooltip tooltip-left">
                        <div class="tooltip-content bg-gray-600"></div>

                    </div> --}}
                </div>
            </div>
        </div>
    </div>

</div>
