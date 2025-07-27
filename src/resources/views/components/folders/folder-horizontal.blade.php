@props([
    'id',
    'name',
    'tags' => '',
    'elementAttribute' => 'data-element',
    'elementAttributeAction' => 'data-element-action',
    'elementAttributeType' => 'data-element-type',
])

<x-horizontal-container>
    <div x-bind:id="'folderContainer-' + {{ $id }}" x-bind:{{ $elementAttribute }}='{{ $id }}'
        {{ $elementAttributeAction }}='open' {{ $elementAttributeType }}="folder" class="h-20 flex">
        <div class="w-1/4 flex-none overflow-hidden">
            <x-html.icons.folder size="85" />
        </div>

        <div class="w-3/4 flex-none overflow-hidden py-1">
            <div class="grid h-full grid-cols-1 content-between">
                <div class="truncate text-xl/6 font-medium pe-2" x-text="{{ $name }}"
                    x-bind:title="{{ $name }}">
                </div>

                <div class="flex justify-start items-center gap-1 ">
                    <template x-for="item in {{ $tags }}">
                        <x-html.tags.tag xText="item.name">

                        </x-html.tags.tag>
                    </template>
                </div>

                <div class="absolute bottom-0 right-0">
                    <x-html.tooltip direction='left' tip='Edit' class="flex-none">
                        <div x-bind:{{ $elementAttribute }}='{{ $id }}' {{ $elementAttributeAction }}='edit'
                            class="flex-none btn btn-sm bg-gray-600 opacity-20 hover:opacity-100 border-0 text-gray-100 shadow-none px-2 transition">
                            {{-- <img src="img/icons/three-dots-vertical.svg"> --}}
                            <x-html.icons.three-dots />
                        </div>
                    </x-html.tooltip>
                </div>

            </div>
        </div>
    </div>
</x-horizontal-container>
