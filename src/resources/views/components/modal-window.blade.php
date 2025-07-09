@props(['id', 'title', 'closeButtonAction' => null])

<!-- You can open the modal using ID.showModal() method -->
<dialog x-data id="{{ $id }}" class="modal">
    {{-- <div class="modal-backdrop bg-gray-800"></div> --}}
    <div class="modal-box rounded-sm sm:w-3/5 md:w-1/2 lg:w-1/5 bg-gray-600 text-gray-100 delay-0">
        <form method="dialog">
            <button @@click="{{ $closeButtonAction }}"
                class="btn btn-sm btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="text-lg font-bold" x-text="{{ $title }}"></h3>
        {{-- <p class="py-3"> --}}
        {{ $slot }}
        {{-- </p> --}}
    </div>
</dialog>
