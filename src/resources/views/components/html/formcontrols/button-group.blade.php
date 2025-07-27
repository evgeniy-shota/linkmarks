@props([
    'canDeleted' => '',
    'deleteAction' => '',
    'saveAction' => '',
    'clearAction' => '',
])

<div x-data="{ confirmDelete: false }" class="flex gap-4 mt-4 justify-center w-full">
    <template x-if="confirmDelete">
        <div>
            <x-captions.warning class="mb-2">
                The data will be deleted without the possibility of
                recovery. Delete?
            </x-captions.warning>
            <div class="flex justify-center items-center gap-4 w-full">
                <x-html.button-out-gray action="confirmDelete=false">
                    Cancel
                </x-html.button-out-gray>

                <x-html.button-out-orange
                    action="confirmDelete=deleteAction({{ $deleteAction }})">
                    Delete
                </x-html.button-out-orange>
            </div>
        </div>

    </template>

    <template x-if="!confirmDelete">
        <div class="flex justify-center items-center gap-3 w-full">
            <x-html.button-out-orange x-show="{{ $canDeleted }}" class="w-1/4"
                action="confirmDelete=true">
                {{-- <x-html.icons.trash /> --}}
                Delete
            </x-html.button-out-orange>

            <x-html.button-out-gray type="reset" action="{{ $clearAction }}"
                class="w-1/4">
                {{-- <x-html.icons.x-lg/> --}}
                Clear
            </x-html.button-out-gray>

            <x-html.button-out-green type="submit" class="w-1/4"
                action="{{ $saveAction }}">
                {{-- <x-html.icons.floppy /> --}}
                Save
            </x-html.button-out-green>
        </div>
    </template>
</div>

<script>
    function deleteAction(callback) {
        callback()
        return false
    }
</script>
