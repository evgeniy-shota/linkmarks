<div class="flex justify-start items-center gap-1 bg-gray-500 hover:bg-gray-400 rounded w-full cursor-pointer overflow-hidden transition my-1 py-1 px-2"
    @@click="$store.bookmark.context_id=item.id" x-bind:title="item.name"
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
