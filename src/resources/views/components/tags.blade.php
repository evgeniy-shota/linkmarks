 <x-html.dropdown class="w-[58vw] sm:w-[45vw] md:w-[35vw] lg:w-[25vw] xl:w-[22vw]">
     <x-slot:button>
         <x-html.button-out-gray action="getTags(Alpine.store('tags').setTags)">
             <x-html.icons.tag />
             <div class="hidden md:block">
                 Tags
             </div>
         </x-html.button-out-gray>
     </x-slot:button>
     <x-slot:content>
         <template x-if="$store.tags.isLoading">
             <div class="absolute flex justify-center w-full h-full top-0 left-0 bg-gray-800/70 rounded">
                 <span class="loading loading-spinner loading-sm"></span>
             </div>
         </template>

         <div class="flex justify-center items-center mb-2">
             <x-html.button-out-gray action="tagModal.showModal()" class="btn-sm text-base font-normal flex-none">
                 <x-html.icons.plus />
                 Create tag
             </x-html.button-out-gray>
         </div>

         <div class="border-b-2 border-gray-500 mb-2"></div>

         <div class="grid grid-cols-3 gap-2 max-h-80 overflow-x-hidden overflow-y-auto">
             <template x-for="(item, index) in $store.tags.tags" ::key="index">
                 <x-html.tags.tag data-tag="index" xText="item.name" class="cursor-pointer" x-on:click="editTag(index)">
                     <x-slot:prefix>
                         <x-html.icons.pencil size="14" />
                     </x-slot:prefix>
                 </x-html.tags.tag>
             </template>
         </div>
     </x-slot:content>
 </x-html.dropdown>
