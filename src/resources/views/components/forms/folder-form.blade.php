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
                <div class="font-medium">
                    Location:
                </div>

                <x-html.dropdown
                    class="w-[45vw] max-h-[25vh] sm:w-[25vw] md:w-[20vw] lg:w-[15vw] xl:w-[12vw] py-1 px-2 overflow-y-auto overflow-x-hidden">
                    <x-slot:button>
                        <x-html.button-out-gray
                            class="btn-sm text-base font-normal"
                            action="getAdditionalDataContext">
                            <x-html.icons.folder />
                            {{-- <div x-text="$store.contexts.currentContext.name"></div> --}}
                            <div
                                x-text="$store.additionalData.getContext($store.context.parentContextId).name">
                            </div>
                        </x-html.button-out-gray>
                    </x-slot:button>

                    <x-slot:content>
                        <div class="flex-col justify-center items-center">
                            <template
                                x-for="item in $store.additionalData.contexts">
                                {{-- <div class="mb-1 flex-none"> --}}
                                <div class="flex justify-start items-center gap-1 bg-gray-500 hover:bg-gray-400 rounded w-full cursor-pointer overflow-hidden transition my-1 py-1 px-2"
                                    @@click="item.id!=$store.context.id ? $store.context.parentContextId=item.id : ''"
                                    x-bind:title="item.name"
                                    x-bind:class="{
                                        'text-gray-300': item.id == $store
                                            .context
                                            .id,
                                        'text-sky-300': item.id == $store
                                            .context.parentContextId
                                    }">
                                    <div class="flex-none">
                                        <x-html.icons.folder />
                                    </div>
                                    <div class="flex-none" x-text="item.name">
                                    </div>
                                </div>
                                {{-- </div> --}}
                            </template>
                        </div>
                    </x-slot:content>
                </x-html.dropdown>
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

            {{-- tags --}}
            <x-html.formcontrols.fieldset title="Tags">
                <x-slot:field>
                    <div
                        class="flex justify-start items-center gap-1 sm:gap-2 text-base">

                        <template x-for="(item,index) of $store.context.tags">
                            <x-html.tags.tag-with-close xText="item.name"
                                closeClick="$store.context.tags.splice(index,1)">
                            </x-html.tags.tag-with-close>
                        </template>

                        {{-- tags list --}}
                        <template x-if="$store.context.tags.length<3">

                            <x-html.dropdown dropdownPosition="dropdown-top dropdown-end"
                                class="w-[30vw] max-h-[25vh] sm:w-[20vw] md:w-[15vw] lg:w-[10vw] xl:w-[8vw] py-1 px-2 overflow-y-auto">
                                <x-slot:button>
                                    <x-html.button-out-gray
                                        class="btn-sm text-base font-normal"
                                        action="getTags(Alpine.store('tags').setTags)">
                                        Add tags
                                    </x-html.button-out-gray>
                                </x-slot:button>

                                <x-slot:content>
                                    <div class="w-full mb-1">
                                        <x-html.button-out-gray
                                            class="btn-sm w-full"
                                            action="tagModal.showModal()">
                                            Create tag
                                        </x-html.button-out-gray>
                                    </div>

                                    <template x-for="item in $store.tags.tags">
                                        <div class="mb-1">
                                            <x-html.tags.tag xText="item.name"
                                                class="py-1 cursor-pointer"
                                                x-on:click="$store.context.addTag(item)">
                                            </x-html.tags.tag>
                                        </div>
                                    </template>
                                </x-slot:content>
                            </x-html.dropdown>
                        </template>
                    </div>
                </x-slot:field>
                <x-slot:legend>
                    Max 3 tags
                </x-slot:legend>
            </x-html.formcontrols.fieldset>


            {{-- bookmark thumbnail preview --}}
            <div class="hidden">
                <div class="font-bold">Thumbnail</div>

                <template x-if="$store.context.thumbnails!==null">
                    <div class="w-full">
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
                <div x-show="$store.context.thumbnails===null">
                    <x-html.formcontrols.input-file-drop-down
                        id="{{ $folderThumbnailInput }}" :required="false" />
                </div>
            </div>


            <x-html.formcontrols.button-group deleteAction="deleteFolder"
                clearActtion="clearForm({{ $folderThumbnailInput }})"
                canDeleted="{{ $canDeleted }}" />

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

            function clearForm(fileInput) {
                console.log(fileInput);
                Alpine.store('context').clearThumbnail()
                Alpine.store('fileInput').clearData(fileInput)
            }
        </script>

    </div>
</div>
