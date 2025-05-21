@props(['breadcrumbs' => null, 'onclick' => null])
@php

@endphp

<div class="breadcrumbs text-md font-semibold bg-gray-700 px-2 rounded-sm">
    <ul>
        <template x-for="(item,index) in {{ $breadcrumbs }}">
            <li @@click="{{ $onclick }}"
                x-bind:data-breadcrumb="index">
                <x-html.breadcrumbs-item text="item.name" index="index" />
            </li>
        </template>
    </ul>
</div>
