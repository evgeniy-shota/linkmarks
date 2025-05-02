@php
    $bookmarkId = 'bookmarkContainer' . $id;
    // $onClickAction = $onClick . '($event,\'' . $link . "')";
@endphp

<div x-data
    class="relative inline-block bg-gray-600 text-gray-100 hover:bg-gray-500 box-border rounded-md w-full px-1 py-1 mx-auto my-1 ">

    <div id="{{ $bookmarkId }}" class="h-20 flex" data-bookmark='{{ $link }}' data-bookmark-action='open'>
        <div class="w-1/4 flex-none overflow-hidden">

            <div class="absolute top-0 left-0">
                <div data-bookmark='{{ $link }}' data-bookmark-action='open-new-tab'
                    class="btn btn-sm bg-gray-600 border-gray-600 text-gray-100 shadow-md opacity-50 hover:opacity-100 px-2">
                    <img src="img/icons/box-arrow-up-right.svg">
                </div>
                {{-- <div data-bookmark='{{ $link }}' data-bookmark-action='copy'
                    class="btn btn-sm bg-gray-600 border-gray-600 text-gray-100 shadow-md opacity-50 hover:opacity-100 px-2">
                    <img src="img/icons/copy.svg">
                </div> --}}
            </div>

            <div class="flex justify-center items-center h-full">
                <picture>
                    <img src="{{ $logo }}" alt="" class="flex-none">
                </picture>
            </div>
        </div>

        <div class="w-3/4 flex-none overflow-hidden">
            <div class="grid h-full grid-cols-1 content-between">
                <div class="truncate text-lg ">{{ $name }} </div>
                <div class="flex justify-between items-center">
                    <div>

                        <small data-bookmark='{{ $link }}' data-bookmark-action='copy'
                            class="px-1 rounded-sm bg-gray-500 text-gray-100 cursor-pointer"> {{ $link }} </small>
                    </div>

                    <div data-bookmark='{{ $id }}' data-bookmark-action='settings'
                        class="btn btn-sm bg-gray-600 border-gray-600 text-gray-100 shadow-md px-2">
                        <img src="img/icons/three-dots-vertical.svg">
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
