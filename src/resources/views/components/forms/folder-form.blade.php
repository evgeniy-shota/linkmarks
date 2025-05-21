@props(['modalId', 'canDeleted' => false])

@php
    $folderThumbnailInput = 'folderThumbnailInput';
@endphp

<div class="flex justify-center items-center w-full">
    <div class="rounded-md bg-gray-600 p-1 w-full">

        <form x-data @@submit.prevent="submitFolderForm"
            action="" method="post">
            @csrf

            <div class="flex items-center gap-1">
                <div class="font-medium">Location:
                </div>
                <x-html.icons.folder />
                <div x-text="$store.contexts.currentContext.name"></div>
            </div>

            {{-- name input --}}
            <x-html.formcontrols.fieldset title='Name' class="mb-2">
                <x-slot:field>
                    <x-html.formcontrols.input required id="name"
                        type="text" placeholder="Youtube"
                        x-model="$store.context.name" :state="true" />
                </x-slot:field>
                <x-slot:legend>
                    <x-html.formcontrols.fieldset-legend
                        text="Enter folder name" />
                </x-slot:legend>
            </x-html.formcontrols.fieldset>

            {{-- bookmark thumbnail preview --}}
            <template x-if="$store.context.thumbnail_id!==null">
                <div class="w-full">
                    <div class="text-base font-bold mb-1">Thumbnail</div>
                    <div
                        class="border-2 border-dashed rounded-sm border-gray-500 flex justify-between items-center h-32 w-full">
                        <div class="flex-none w-1/2">
                            <x-html.thumbnail id="contextThumbnailPreview"
                                src=""
                                xSrc="$store.context.thumbnail" />
                        </div>
                        <div class="w-1/2" class="flex-none">
                            <x-html.button
                                action="Alpine.store('context').clearThumbnail()">
                                Clear
                            </x-html.button>
                        </div>
                    </div>
                </div>

            </template>

            {{-- file input --}}
            <div class="font-bold">Thumbnail</div>
            <div x-show="$store.context.thumbnail_id===null">
                <x-html.formcontrols.input-file-drop-down
                    id="{{ $folderThumbnailInput }}" :required="false" />
            </div>

            {{-- <div class="flex justify-around items-center mt-2">
                <button type="reset"
                    class="btn bg-gray-500 border-gray-600 hover:border-gray-500 text-gray-100 shadow-md">
                    Clear
                </button>

                <button type="submit"
                    class="btn bg-gray-500 border-gray-600 hover:border-gray-500 text-gray-100 shadow-md">
                    Submit
                </button>
            </div> --}}

            <div class="flex gap-4 mt-4 justify-center w-full">
                <x-html.button-out-orange x-show="{{ $canDeleted }}"
                    action="deleteFolder()">
                    Delete
                </x-html.button-out-orange>

                <x-html.button-out-gray type="reset"
                    action="clearForm({{ $folderThumbnailInput }})"
                    class="w-1/4">
                    <x-html.icons.x-lg />
                    Clear
                </x-html.button-out-gray>

                <x-html.button-out-green type="submit" class="w-1/4">
                    <x-html.icons.floppy />
                    Save
                </x-html.button-out-green>
            </div>
        </form>

        <script>
            async function submitFolderForm() {
                let data = Alpine.store('context').getData();

                if (Alpine.store('context').id === null) {
                    createFolder(data)
                } else {
                    updateFolder(data)
                }
            }

            async function createFolder(data) {

                let result = await submitForm(
                    data,
                    '/context/',
                    (context) => Alpine.store('contexts').data.push(context))
                console.log(Alpine.store('contexts').data)
                if (result) {
                    {{ $modalId }}.close()
                    closeModal()
                    Alpine.store('alerts').addAlert('Folder added successfully',
                        'success')
                }
            }

            async function updateFolder(data) {
                let index = Alpine.store('context').indexInContexts

                let result = await updateRequest(
                    '/contexts/' + data.id,
                    data,
                    (context) => Alpine.store('contexts').data.push(context))

                if (result) {
                    Alpine.store('contexts').data[index] = result
                    {{ $modalId }}.close()
                    closeModal()
                    Alpine.store('alerts').addAlert('Folder updated successfully',
                        'success')
                } else {
                    Alpine.store('alerts').addAlert('Failed! Folder not updated!',
                        'warning')
                }
            }

            async function deleteFolder() {

                let result = await deleteRequest(
                    '/contexts/',
                    Alpine.store('context').id);

                if (result) {
                    Alpine.store('contexts').data.splice(
                        Alpine.store('context').indexInContexts,
                        1);
                    {{ $modalId }}.close()
                    closeModal()
                    Alpine.store('alerts').addAlert('Folder removed!', 'success');
                } else {
                    Alpine.store('alerts').addAlert('Failed! Folder not removed!',
                        'warning')
                }
            }

            function clearForm(fileInput) {
                console.log(fileInput);
                Alpine.store('context').clearThumbnail()
                Alpine.store('fileInput').clearData(fileInput)
            }
        </script>

    </div>
</div>
