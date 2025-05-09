@props(['breadcrumbs' => null, 'onclick' => null])
@php

@endphp

<div class="breadcrumbs text-sm ">
    <ul>
        <li>
            {{ $slot }}
        </li>
        <li><a>Home</a></li>
        <li><a>Documents</a></li>
        <li>Add Document</li>
    </ul>
</div>
