@props(['id' => '', 'value' => 'on'])

<div class="inline" x-data="{ checked: true }">
    <label for="{{ $id }}"
        class="border-box flex justify-start items-center gap-2 border-b-1 border-gray-400/0 hover:border-gray-400 transition">

        <div class="flex-none">
            <div x-show="checked">
                <x-html.icons.check-square size="16" />
            </div>

            <div x-show="!checked">
                <x-html.icons.square size="16" />
            </div>
        </div>

        <div>
            {{ $label }}
        </div>
    </label>
    <input value="{{ $value }}" type="checkbox" x-model="checked"
        name="{{ $id }}" id="{{ $id }}" class="hidden">
</div>
