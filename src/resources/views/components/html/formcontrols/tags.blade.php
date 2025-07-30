@props(['targetStore'])

<x-html.formcontrols.fieldset title="Tags">
    <x-slot:field>
        <div class="flex justify-start items-center gap-1 sm:gap-2 text-base">

            <template x-for="(item,index) of $store.{{ $targetStore }}.tags">
                <x-html.tags.tag-with-close xText="item.name"
                    closeClick="$store.{{ $targetStore }}.tags.splice(index,1)">
                </x-html.tags.tag-with-close>
            </template>

            {{-- tags list --}}
            <template x-if="$store.{{ $targetStore }}.tags.length<3">

                <x-html.dropdown dropdownPosition="dropdown-top "
                    class="w-[30vw] max-h-[22vh] sm:w-[20vw] md:w-[15vw] lg:w-[10vw] xl:w-[8vw] py-1 px-2 overflow-y-auto">
                    <x-slot:button>
                        <x-html.button-out-gray class="btn-sm text-base font-normal"
                            action="getTags(Alpine.store('tags').setTags)">
                            Add tags
                        </x-html.button-out-gray>
                    </x-slot:button>

                    <x-slot:content>
                        <div class="w-full mb-1">
                            <x-html.button-out-blue class="btn-sm w-full text-sm"
                                action="showTagModal((tag)=>Alpine.store('{{ $targetStore }}').addTag(tag))">
                                Create tag
                            </x-html.button-out-blue>
                        </div>

                        <template x-for="item in $store.tags.tags">
                            <div class="mb-1">
                                <x-html.tags.tag xText="item.name" class="py-1 cursor-pointer"
                                    x-on:click="$store.{{ $targetStore }}.addTag(item)">
                                </x-html.tags.tag>
                            </div>
                        </template>

                        <script>
                            function showTagModal(callback = null) {
                                tagModal.showModal()

                                if (callback !== null) {
                                    Alpine.store('tag').callback = callback
                                    console.log(Alpine.store('tag').callback);
                                }
                            }
                        </script>
                    </x-slot:content>
                </x-html.dropdown>
            </template>
        </div>
    </x-slot:field>
    <x-slot:legend>
        Max 3 tags
    </x-slot:legend>
</x-html.formcontrols.fieldset>
