@props([
    'id',
    'name',
    'elementAttribute' => 'data-element',
    'elementAttributeAction' => 'data-element-action',
    'elementAttributeType' => 'data-element-type',
])

{{-- <div x-data
    class="relative inline-block bg-gray-600 text-gray-100 hover:bg-gray-500 hover:text-gray-50 box-border rounded-md w-full px-1 py-1 transition duration-150"> --}}
<x-horizontal-container>
    <div x-bind:id="'folderContainer-' + {{ $id }}"
        x-bind:{{ $elementAttribute }}='{{ $id }}'
        {{ $elementAttributeAction }}='open' {{ $elementAttributeType }}="folder"
        class="h-20 flex">
        <div class="w-1/4 flex-none overflow-hidden">
            <div class="flex flex-col justify-center items-center h-full">
                {{-- <picture>
                    <img src="{{ $thumbnail }}" alt="" class="flex-none">
                </picture> --}}
                <div
                    class="flex flex-none w-full h-1/2 justify-center items-center">
                    <div class="flex-none w-1/2 h-full p-1">
                        <div class="w-full h-full bg-sky-600 rounded-sm"></div>
                    </div>
                    <div class="flex-none w-1/2 h-full p-1">
                        <div class="w-full h-full bg-sky-600 rounded-sm"></div>
                    </div>
                </div>
                <div
                    class="flex flex-none w-full h-1/2 justify-center items-center">
                    <div class="flex-none w-1/2 h-full p-1">
                        <div class="w-full h-full bg-sky-600 rounded-sm"></div>
                    </div>
                    <div class="flex-none w-1/2 h-full p-1">
                        <div class="w-full h-full bg-sky-600 rounded-sm"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="w-3/4 flex-none overflow-hidden">
            <div class="grid h-full grid-cols-1 content-between">
                <div class="truncate text-lg/6 font-medium py-1 pe-2"
                    x-text="{{ $name }}">
                </div>
                {{-- <div class="flex flex-row-reverse items-center">
                    <x-html.tooltip direction='left' tip='Edit'>
                        <div x-bind:{{ $elementAttribute }}='{{ $id }}'
                            {{ $elementAttributeAction }}='edit'
                            class="btn btn-sm bg-gray-600 hover:bg-gray-600 border-0 text-gray-100 shadow-none px-2">
                            <img src="img/icons/three-dots-vertical.svg">
                        </div>
                    </x-html.tooltip>
                </div> --}}

                <div class="absolute bottom-0 right-0">
                    <x-html.tooltip direction='left' tip='Edit'
                        class="flex-none">
                        <div x-bind:{{ $elementAttribute }}='{{ $id }}'
                            {{ $elementAttributeAction }}='edit'
                            class="flex-none btn btn-sm bg-gray-600 opacity-50 hover:opacity-100 border-0 text-gray-100 shadow-none px-2">
                            <img src="img/icons/three-dots-vertical.svg">
                        </div>
                    </x-html.tooltip>
                </div>

            </div>
        </div>
    </div>
</x-horizontal-container>
{{-- </div> --}}
