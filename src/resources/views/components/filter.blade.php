<x-html.dropdown class="w-[80vw] sm:w-[47vw] md:w-[37vw] lg:w-[30vw]">
    <x-slot:button>
        <x-html.button-out-gray class="relative" action="getTags(Alpine.store('tags').setTags)">
            <x-html.icons.funnel />
            <div class="hidden md:block">
                Filter
            </div>

            <template x-if="$store.filter.isApplied">
                <div aria-label="info" class="absolute top-0 right-0 w-[8px] h-[8px] bg-sky-400 rounded-sm">
                </div>
            </template>
        </x-html.button-out-gray>
    </x-slot:button>

    <x-slot:content>
        <template x-if="$store.tags.isLoading">
            <div class="absolute flex justify-center w-full h-full top-0 left-0 bg-gray-800/70 rounded">
                <span class="loading loading-spinner loading-sm"></span>
            </div>
        </template>

        <div class="flex justify-center items-center gap-2 mb-2">
            <x-html.button-out-blue class="btn-sm text-base font-semibold" action="applyFilter()">
                Apply filter
            </x-html.button-out-blue>
            <x-html.button-out-gray class="btn-sm text-base font-semibold" action="declineFilter()">
                Decline filter
            </x-html.button-out-gray>
        </div>

        <div class="flex flex-col justify-center items-start mb-1">

            <x-html.formcontrols.checkbox-button condition="$store.filter.applyToContexts"
                action="$store.filter.applyToContexts=!$store.filter.applyToContexts">
                <x-slot:label>
                    apply to folders
                </x-slot:label>
            </x-html.formcontrols.checkbox-button>

            <x-html.formcontrols.checkbox-button condition="$store.filter.applyToBookmarks"
                action="$store.filter.applyToBookmarks=!$store.filter.applyToBookmarks">
                <x-slot:label>
                    apply to bookmarks
                </x-slot:label>
            </x-html.formcontrols.checkbox-button>

            <x-html.tooltip bgColor="bg-gray-700">
                <x-slot:tip>
                    <div class="p-1">
                        If set, the filter applies only to bookmarks and/or folders in the <span
                            class="font-semibold">current</span> folder
                    </div>
                </x-slot:tip>

                <x-html.formcontrols.checkbox-button condition="$store.filter.contextualFiltration"
                    action="$store.filter.togglecontextualFiltration(Alpine.store('contexts').currentContext.id)">
                    <x-slot:label>
                        contextual filtering
                    </x-slot:label>
                </x-html.formcontrols.checkbox-button>
            </x-html.tooltip>

        </div>

        <div class="border-b-2 border-gray-500 mb-2"></div>

        <div class="flex justify-around items-center mb-2">
            <x-html.button-out-gray action="$store.tags.setAllTagsState(null)" class="btn-sm px-2">
                <x-html.icons.square />
                <div class="text-base font-normal">
                    - not use
                </div>
            </x-html.button-out-gray>

            <x-html.button-out-gray action="$store.tags.setAllTagsState(true)" class="btn-sm px-2">
                <x-html.icons.check-square />
                <div class="text-base font-normal">
                    - incl
                </div>
            </x-html.button-out-gray>

            <x-html.button-out-gray action="$store.tags.setAllTagsState(false)" class="btn-sm px-2">
                <x-html.icons.x-square />
                <div class="text-base font-normal">
                    - excl
                </div>
            </x-html.button-out-gray>

        </div>
        <div class="border-b-2 border-gray-500 mb-2"></div>

        <template x-if="!$store.tags.isLoading && $store.tags.tags.length==0">
            <div>You have no tags</div>
        </template>

        <div @@click="clickOnTag($event)"
            class="sm:w-[28vw] sm:max-h-[20vh] w-[70vw] overflow-y-auto grid grid-cols-3 gap-2">
            <template x-for="(item, index) in $store.tags.tags">
                <x-html.tags.tag-checkbox background="#3b82f6" state="item.state" xText="item.name"
                    x-bind:data-tag="index">
                </x-html.tags.tag-checkbox>
            </template>
        </div>
    </x-slot:content>
</x-html.dropdown>
