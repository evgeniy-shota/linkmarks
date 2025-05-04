@props(['action' => null, 'type' => 'button'])

{{-- onclick="bookmarksModal.showModal() --}}

<button onclick="{{ $action }}" type="{{ $type }}"
    {{ $attributes->merge(['class' => 'btn bg-gray-500 border-gray-600 hover:border-gray-500 text-gray-100 shadow-md']) }}>
    {{ $slot }}
</button>
