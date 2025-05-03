@props(['action'])

{{-- onclick="bookmarksModal.showModal() --}}

<button onclick="{{ $action }}"
    {{ $attributes->merge(['class' => 'btn bg-gray-500 border-gray-600 hover:border-gray-500 text-gray-100 shadow-md']) }}>
    {{ $slot }}
</button>
