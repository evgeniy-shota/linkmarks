<x-html.dropdown
    class="w-[45vw] max-h-[25vh] sm:w-[25vw] md:w-[20vw] lg:w-[15vw] xl:w-[12vw] py-1 px-2 overflow-y-auto overflow-x-hidden">
    <x-slot:button>
        <x-html.button-out-gray class="btn-sm text-base font-normal" action="getAdditionalDataContext">
            <x-html.icons.folder />
            {{ $selectedLocation }}
        </x-html.button-out-gray>
    </x-slot:button>

    <x-slot:content>
        <div class="flex-col justify-center items-center">
            <template x-for="item in $store.additionalData.contexts">
                {{ $item }}
            </template>
        </div>
    </x-slot:content>
</x-html.dropdown>
