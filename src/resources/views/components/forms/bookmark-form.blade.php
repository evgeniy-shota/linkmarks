@props(['modalId', 'canDeleted' => false])
@php
    $thumbnailInput = 'thumbnailInput';
@endphp

<div class="flex justify-center items-center w-full">
    <div class="rounded-md bg-gray-600 p-1 w-full">

        <form x-data @@submit.prevent="submitBookmarkForm"
            action="" method="post">
            @csrf

            <div class="flex items-center gap-1">
                <div class="font-medium">Location:
                </div>

                {{-- <x-html.button-out-gray class="btn-sm text-base font-normal">
                    <x-html.icons.folder />
                    <div x-text="$store.contexts.currentContext.name"></div>
                </x-html.button-out-gray> --}}

                <div class="dropdown dropdown-center">

                    <x-html.button-out-gray class="btn-sm text-base font-normal"
                        action="getAdditionalDataContext">
                        <x-html.icons.folder />
                        {{-- <div x-text="$store.contexts.currentContext.name"></div> --}}
                        <div
                            x-text="$store.additionalData.getContext($store.bookmark.context_id).name">
                        </div>
                    </x-html.button-out-gray>

                    <div tabindex="0"
                        class="dropdown-content menu bg-gray-700 rounded-box z-1 sm:w-[15vw] sm:max-h-[25vh] py-1 px-2 overflow-y-auto overflow-x-hidden">

                        <div class="flex-col justify-center items-center">
                            <template
                                x-for="item in $store.additionalData.contexts">
                                {{-- <div class="mb-1 flex-none"> --}}
                                <div class="bg-gray-500 hover:bg-gray-400 rounded w-full cursor-pointer transition my-1 py-1 px-2"
                                    x-text="item.name"
                                    @@click="$store.bookmark.context_id=item.id">

                                </div>
                                {{-- </div> --}}
                            </template>
                        </div>

                    </div>
                </div>

                {{-- <x-html.icons.folder />
                <div x-text="$store.contexts.currentContext.name"></div> --}}
            </div>

            {{-- link input --}}
            <x-html.formcontrols.fieldset title='Link'>
                <x-slot:field>
                    <x-html.formcontrols.input
                        x-on:change="linkInputFocusHandler($event)" required
                        id="link" type="text"
                        placeholder="www.youtube.com"
                        x-model="$store.bookmark.link" :state="true" />
                </x-slot:field>
                <x-slot:legend>
                    <x-html.formcontrols.fieldset-legend text="Enter link" />
                </x-slot:legend>
            </x-html.formcontrols.fieldset>

            {{-- name input --}}
            <x-html.formcontrols.fieldset title='Name' class="mb-2">
                <x-slot:field>
                    <x-html.formcontrols.input required id="name"
                        type="text" placeholder="Youtube"
                        x-model="$store.bookmark.name" :state="true" />
                </x-slot:field>
                <x-slot:legend>
                    <x-html.formcontrols.fieldset-legend
                        text="Enter bookmark name" />
                </x-slot:legend>
            </x-html.formcontrols.fieldset>

            {{-- tags --}}
            <x-html.formcontrols.fieldset title="Tags">
                <x-slot:field>
                    <div
                        class="flex justify-start items-center gap-2 text-base">

                        <template x-for="(item,index) of $store.bookmark.tags">
                            <x-html.tags.tag-with-close xText="item.name"
                                closeClick="$store.bookmark.tags.splice(index,1)">
                            </x-html.tags.tag-with-close>
                        </template>

                        {{-- tags list --}}
                        <template x-if="$store.bookmark.tags.length<3">
                            <div class="dropdown dropdown-top dropdown-center">

                                <x-html.button-out-gray
                                    class="btn-sm text-base font-normal"
                                    action="getTags(Alpine.store('tags').setTags)">
                                    Add tags
                                </x-html.button-out-gray>

                                <div tabindex="0"
                                    class="dropdown-content menu bg-gray-700 rounded-box z-1 sm:w-[12vw] sm:max-h-[25vh] py-1 px-2 overflow-y-auto">
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
                                                x-on:click="$store.bookmark.addTag(item)">
                                            </x-html.tags.tag>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>
                    </div>
                </x-slot:field>
                <x-slot:legend>
                    Max 3 tags
                </x-slot:legend>
            </x-html.formcontrols.fieldset>


            {{-- bookmark thumbnail preview --}}
            <div class="font-bold">Thumbnail</div>
            <template x-if="$store.bookmark.thumbnail_id!==null">
                <div class="w-full">
                    {{-- <div class="text-base font-bold mb-1">Thumbnail</div> --}}
                    <div
                        class="border-2 border-dashed rounded-sm border-gray-500 flex justify-between items-center h-32 w-full">
                        <div class="flex-none w-1/2">
                            <x-html.thumbnail id="bookmarkThumbnailPreview"
                                src=""
                                xSrc="$store.bookmark.thumbnail" />
                        </div>
                        <div class="w-1/2" class="flex-none">
                            <x-html.button
                                action="Alpine.store('bookmark').clearThumbnail()">
                                Clear
                            </x-html.button>
                        </div>
                    </div>
                </div>

            </template>

            {{-- file input --}}
            <div x-show="$store.bookmark.thumbnail_id===null" class="mb-2">
                <x-html.formcontrols.input-file-drop-down
                    id="{{ $thumbnailInput }}" :required="false" />
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

            <x-html.formcontrols.button-group deleteAction="deleteBookmark"
                clearActtion="clearForm({{ $thumbnailInput }})"
                canDeleted="{{ $canDeleted }}" />

        </form>

        <script>
            function linkInputFocusHandler(e) {
                if (e.target.value.length === 0) {
                    return
                }

                getAutocompleteData(e.target.value)
            }

            async function getAutocompleteData(link) {
                let url = '/autofill-bf';
                url += '?url=' + link;

                let response = getRequest(url);

                console.log(response);
            }

            async function submitBookmarkForm() {
                let data = Alpine.store('bookmark').getData()

                if (Alpine.store('bookmark').id === null) {
                    createBookmark(data)
                } else {
                    updateBookmark(data)
                }

            }

            async function createBookmark(data) {
                let result = await submitForm(
                    data,
                    '/bookmarks/',
                    function(bookmark) {
                        if (bookmark.context_id == Alpine.store('contexts')
                            .currentContext.id) {
                            Alpine.store('contexts').data.push(bookmark)
                        }
                    })

                console.log(Alpine.store('contexts').data)

                if (result) {
                    {{ $modalId }}.close()
                    Alpine.store('alerts').addAlert('Bookmark added successfully',
                        'success')
                    clearBookmarkStore()
                } else {
                    Alpine.store('alerts').addAlert('Failed! Bookmark not added!',
                        'warning')
                }
            }

            async function updateBookmark(data) {
                let index = Alpine.store('bookmark').indexInContexts

                let result = await updateRequest(
                    '/bookmarks/' + data.id,
                    data)

                if (result) {
                    if (result.context_id != Alpine.store('contexts')
                        .currentContext.id) {
                        Alpine.store('contexts').data.splice(index, 1)
                    } else {
                        Alpine.store('contexts').data[index] = result
                    }

                    {{ $modalId }}.close()
                    clearBookmarkStore()
                    Alpine.store('alerts').addAlert('Bookmark updated successfully',
                        'success')
                } else {
                    Alpine.store('alerts').addAlert('Failed! Bookmark not updated!',
                        'warning')
                }
            }

            async function deleteBookmark() {

                let result = await deleteRequest(
                    '/bookmarks/',
                    Alpine.store('bookmark').id);

                if (result) {

                    Alpine.store('contexts').data.splice(
                        Alpine.store('bookmark').indexInContexts,
                        1);
                    {{ $modalId }}.close()
                    clearBookmarkStore()
                    Alpine.store('alerts').addAlert('Bookmark removed!', 'success');
                } else {
                    Alpine.store('alerts').addAlert('Failed! Bookmark not removed!',
                        'warning')
                }
            }

            function clearForm(fileInput) {
                console.log(fileInput);
                Alpine.store('bookmark').clearThumbnail()
                Alpine.store('fileInput').clearData(fileInput)
            }
        </script>

    </div>
</div>
