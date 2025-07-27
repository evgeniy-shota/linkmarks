@props(['id', 'title', 'closeButtonAction' => null])
@php
    $classes = 'modal-box rounded-sm sm:w-3/5 md:w-1/2 lg:w-1/3 xl:w-1/4 bg-gray-600 text-gray-100 delay-0';
@endphp

<dialog x-data id="{{ $id }}" class="modal">
    <div {{ $attributes->merge(['class' => $classes]) }}>
        <form method="dialog">
            <button @@click="{{ $closeButtonAction }}"
                class="btn btn-sm btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="text-lg font-bold" x-text="{{ $title }}"></h3>

        {{ $slot }}

    </div>
</dialog>
