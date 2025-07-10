@props(['modalId', 'canDeleted' => false])
@php
    $thumbnailInput = 'thumbnailInput';
@endphp

<div class="flex justify-center items-center w-full">
    <div class="rounded-md bg-gray-600 p-1 w-full">

        <template x-if="$store.additionalData.isLoading">
            <div
                class="absolute top-0 left-0 w-full h-full bg-gray-600/50 z-20 flex justify-center items-center">
                <span class="loading loading-spinner loading-xl"></span>
            </div>
        </template>

        <form x-data @@submit.prevent="submitBookmarkForm"
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
                                x-text="$store.additionalData.getContext($store.bookmark.context_id).name">
                            </div>
                        </x-html.button-out-gray>
                    </x-slot:button>

                    <x-slot:content>
                        <div class="flex-col justify-center items-center">
                            <template
                                x-for="item in $store.additionalData.contexts">
                                {{-- <div class="mb-1 flex-none"> --}}
                                <div class="flex justify-start items-center gap-1 bg-gray-500 hover:bg-gray-400 rounded w-full cursor-pointer overflow-hidden transition my-1 py-1 px-2"
                                    @@click="$store.bookmark.context_id=item.id"
                                    x-bind:title="item.name"
                                    x-bind:class="{
                                        'text-sky-300': item.id == $store
                                            .bookmark
                                            .context_id
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

            {{-- link input --}}
            <x-html.formcontrols.fieldset title='Url'>
                <x-slot:field>
                    <x-html.formcontrols.input
                        x-on:change="linkInputFocusHandler($event)" required
                        id="link" type="url"
                        placeholder="https://www.youtube.com/"
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

                            <x-html.dropdown
                                class="w-[30vw] max-h-[25vh] sm:w-[20vw] md:w-[15vw] lg:w-[10vw] xl:w-[8vw] py-1 px-2 overflow-y-auto overflow-x-hidden">
                                <x-slot:button>
                                    <x-html.button-out-gray
                                        class="btn-sm text-base font-normal"
                                        action="getTags(Alpine.store('tags').setTags)">
                                        Add tags
                                    </x-html.button-out-gray>
                                </x-slot:button>

                                <x-slot:content>
                                    <div
                                        class="flex-col justify-center items-center">
                                        <div class="w-full mb-1">
                                            <x-html.button-out-gray
                                                class="btn-sm w-full"
                                                action="tagModal.showModal()">
                                                Create tag
                                            </x-html.button-out-gray>
                                        </div>

                                        <template
                                            x-for="item in $store.tags.tags">
                                            <div class="mb-1">
                                                <x-html.tags.tag
                                                    xText="item.name"
                                                    class="py-1 cursor-pointer"
                                                    x-on:click="$store.bookmark.addTag(item)">
                                                </x-html.tags.tag>
                                            </div>
                                        </template>
                                    </div>
                                </x-slot:content>
                            </x-html.dropdown>

                            {{-- <div class="dropdown dropdown-top dropdown-center">

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
                            </div> --}}

                        </template>
                    </div>
                </x-slot:field>
                <x-slot:legend>
                    Max 3 tags
                </x-slot:legend>
            </x-html.formcontrols.fieldset>

            <div x-data="{ 'showThumbnailsVariants': false }">
                {{-- bookmark thumbnail preview --}}
                <div class="flex justify-start items-center gap-1 mb-2">
                    <div class="font-bold">Thumbnail</div>
                    <template
                        x-if="$store.additionalData.thumbnails.length>0 || $store.bookmark.id!=null">
                        <x-html.button-out-gray class="btn-sm"
                            action="$store.globalValuesStore.showBookmarkThumbnailsVariants=!$store.globalValuesStore.showBookmarkThumbnailsVariants"
                            x-on:click="$store.globalValuesStore.showBookmarkThumbnailsVariants && getVariantsThumbnails(Alpine.store('bookmark').link)">
                            <template
                                x-if="!$store.globalValuesStore.showBookmarkThumbnailsVariants">
                                <div>Get more variants</div>
                            </template>
                            <template
                                x-if="$store.globalValuesStore.showBookmarkThumbnailsVariants">
                                <div>Hide variants</div>
                            </template>
                        </x-html.button-out-gray>
                    </template>
                </div>

                {{-- showThumbnailsVariants --}}
                <template
                    x-if="$store.globalValuesStore.showBookmarkThumbnailsVariants">
                    <div
                        class="h-43 w-full border-2 border-dashed rounded-sm border-gray-500 overflow-hidden">
                        <div
                            class="h-full grid grid-cols-3 gap-2 pt-1 overflow-y-auto">
                            <template
                                x-for="item in $store.additionalData.thumbnails">
                                <x-html.thumbnail id="bookmarkThumbnailPreview"
                                    src="" xSrc="item.name"
                                    size="24"
                                    class="hover:border-gray-400 cursor-pointer"
                                    x-on:click="$store.bookmark.setThumbnailId(item.id, item.name)"
                                    x-bind:class="{
                                        'border-sky-300': item.id == $store
                                            .bookmark.thumbnail_id
                                    }">
                                    <x-slot:topLeftContainer>
                                        <template
                                            x-if="item.id == $store
                                            .bookmark.thumbnail_id">
                                            <x-html.icons.check-lg />
                                        </template>
                                    </x-slot:topLeftContainer>
                                </x-html.thumbnail>
                            </template>
                        </div>
                    </div>
                </template>

                <template
                    x-if="$store.bookmark.thumbnail_id!==null && !$store.globalValuesStore.showBookmarkThumbnailsVariants">
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
                {{-- <template
                    x-show="!$store.globalValuesStore.showBookmarkThumbnailsVariants"> --}}
                <div x-show="$store.bookmark.thumbnail_id===null && !$store.globalValuesStore.showBookmarkThumbnailsVariants"
                    class="mb-2">
                    <x-html.formcontrols.input-file-drop-down
                        id="{{ $thumbnailInput }}" :required="false" />
                </div>
                {{-- </template> --}}

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
                clearAction="clearBookmarkForm({{ $thumbnailInput }})"
                canDeleted="{{ $canDeleted }}" />

        </form>

        <script>
            function showThumbnailsVariants(callback) {
                console.log('get potential thumbnails')
            }

            function linkInputFocusHandler(e) {
                if (e.target.value.length === 0) {
                    return
                }

                getAutocompleteData(e.target.value)
            }

            async function getVariantsThumbnails(link) {
                Alpine.store('additionalData').isLoading = true
                let url = '/additional-data/bf-thumbnails'
                url += '?url=' + link;

                let response = await getRequest(url);

                if (response) {
                    Alpine.store('additionalData').thumbnails = response;
                }

                Alpine.store('additionalData').isLoading = false
            }

            async function getAutocompleteData(link) {
                Alpine.store('additionalData').isLoading = true
                let url = '/additional-data/bf-autocomplete';
                url += '?url=' + link;

                let response = await getRequest(url);

                if (response) {
                    console.log(response)
                    setAutocompleteData(response)
                }

                Alpine.store('additionalData').isLoading = false

                console.log(response);
            }

            function setAutocompleteData(data) {

                if (Alpine.store('bookmark').name == null ||
                    Alpine.store('bookmark').name.length == 0) {
                    Alpine.store('bookmark').name = data.name
                }

                if (Alpine.store('bookmark').thumbnailFile == null) {
                    Alpine.store('bookmark').thumbnail = data.thumbnails[0].name
                    Alpine.store('bookmark').thumbnail_id = data.thumbnails[0].id
                }

                Alpine.store('additionalData').thumbnails = (data.thumbnails)
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

            function clearBookmarkForm(fileInput) {
                // console.log(fileInput);
                Alpine.store('bookmark').clear()
                Alpine.store('bookmark').context_id = Alpine.store('contexts')
                    .currentContext.id
                Alpine.store('fileInput').clearData(fileInput)
                Alpine.store('globalValuesStore').showBookmarkThumbnailsVariants = false
                Alpine.store('additionalData').clearThumbnails()
            }
        </script>

    </div>
</div>
