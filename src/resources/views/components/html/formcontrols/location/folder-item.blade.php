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
