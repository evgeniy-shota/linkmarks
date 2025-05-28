@props(['breadcrumbs' => null, 'onclick' => null])

<div
    class="breadcrumbs w-full text-md font-semibold bg-gray-700 px-2 rounded-sm">
    <ul>
        <template x-for="(item,index) in {{ $breadcrumbs }}">
            <li @@click="{{ $onclick }}"
                x-bind:data-breadcrumb="index">
                <x-html.breadcrumbs-item text="item.name" index="index"
                    active="Alpine.store('contexts').breadcrumbs.length-1==index" marked="item.type=='search'" />
            </li>
        </template>
    </ul>
</div>
