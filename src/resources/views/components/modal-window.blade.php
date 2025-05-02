@props(['id', 'title'])

<!-- You can open the modal using ID.showModal() method -->
<dialog id="{{ $id }}" class="modal">
    {{-- <div class="modal-backdrop bg-gray-800"></div> --}}
    <div class="modal-box bg-gray-600 text-gray-100 ">
        <form method="dialog">
            <button class="btn btn-sm btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="text-lg font-bold">{{ $title }}</h3>
        <p class="py-4">
            {{ $slot }}
        </p>
    </div>
</dialog>
