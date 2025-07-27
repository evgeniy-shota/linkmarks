@props(['breadcrumbs' => null, 'onclick' => null])

<div
    class="breadcrumbs w-full text-md font-semibold bg-gray-700 px-2 rounded-b-sm">
    <ul>
        <template x-for="(item,index) in {{ $breadcrumbs }}">
            <li @@click="{{ $onclick }}"
                x-bind:data-breadcrumb="index" class="relative">
                <x-html.breadcrumbs-item text="item.name" index="index" isRoot="item.is_root"
                    active="Alpine.store('contexts').breadcrumbs.length-1==index"
                    marked="item.type=='search'" />

                <template
                    x-if="$store.filter.isApplied && $store.filter.currentContextId==item.id">
                    <div aria-label="info"
                        class="absolute top-0 right-0 w-[8px] h-[8px] bg-sky-400 rounded-sm">
                    </div>
                </template>
            </li>
        </template>
    </ul>
</div>
