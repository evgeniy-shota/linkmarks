@props(['modalId', 'canDeleted' => false])

@php
    $folderThumbnailInput = 'folderThumbnailInput';
@endphp

<div class="flex justify-center items-center w-full">
    <div class="rounded-md bg-gray-600 p-1 w-full">

        <form x-data @@submit.prevent="submitFolderForm" action="" method="post">
            @csrf

            <div class="flex items-center gap-1">
                <div class="font-medium">
                    Location:
                </div>

                <x-html.formcontrols.location targetStore="context">
                    <x-slot:selectedLocation>
                        <div x-text="$store.additionalData.getContext($store.context.parentContextId).name">
                        </div>
                    </x-slot:selectedLocation>
                    <x-slot:item>
                        <x-html.formcontrols.location.folder-item />
                    </x-slot:item>
                </x-html.formcontrols.location>
            </div>

            {{-- name input --}}
            <x-html.formcontrols.fieldset title='Name' class="mb-2">
                <x-slot:field>
                    <x-html.formcontrols.input required id="name" type="text" placeholder="Youtube"
                        x-model="$store.context.name" :state="true" />
                </x-slot:field>
                <x-slot:legend>
                    <x-html.formcontrols.fieldset-legend text="Enter folder name" />
                </x-slot:legend>
            </x-html.formcontrols.fieldset>

            {{-- tags --}}
            <x-html.formcontrols.tags targetStore="context" />

            {{-- folder thumbnail preview --}}
            <div class="hidden">
                <div class="font-bold">Thumbnail</div>

                <template x-if="$store.context.thumbnails!==null">
                    <div class="w-full">
                        <div
                            class="border-2 border-dashed rounded-sm border-gray-500 flex justify-between items-center h-32 w-full">
                            <div class="flex-none w-1/2">
                                <x-html.thumbnail id="contextThumbnailPreview" src=""
                                    xSrc="$store.context.thumbnail" />
                            </div>
                            <div class="w-1/2" class="flex-none">
                                <x-html.button action="Alpine.store('context').clearThumbnail()">
                                    Clear
                                </x-html.button>
                            </div>
                        </div>
                    </div>
                </template>

                {{-- file input --}}
                <div x-show="$store.context.thumbnails===null">
                    <x-html.formcontrols.input-file-drop-down id="{{ $folderThumbnailInput }}" :required="false" />
                </div>
            </div>


            <x-html.formcontrols.button-group deleteAction="deleteFolder"
                clearAction="clearFolderForm({{ $folderThumbnailInput }})" canDeleted="{{ $canDeleted }}" />

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
                    function(context) {
                        if (context.parentContextId ==
                            Alpine.store('contexts').currentContext.id) {
                            Alpine.store('contexts').data.push(context)
                        }
                    })

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
                    data)

                if (result) {

                    if (result.parentContextId != Alpine.store('contexts')
                        .currentContext.id) {
                        Alpine.store('contexts').data.splice(index, 1)
                    } else {
                        Alpine.store('contexts').data[index] = result
                    }

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

            function clearFolderForm(fileInput) {
                clearContextStore()
                Alpine.store('context').parentContextId = Alpine.store('contexts')
                    .currentContext.id
                Alpine.store('fileInput').clearData(fileInput)
            }
        </script>

    </div>
</div>
