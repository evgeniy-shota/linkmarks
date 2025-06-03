@props([
    'dropdownPosition' => 'dropdown-center',
    'contentClasses' => '',
    'action' => '',
])

<div class="dropdown {{ $dropdownPosition }}">
    {{ $button }}

    <div tabindex="0"
        {{ $attributes->merge(['class' => 'dropdown-content relative rounded bg-gray-600 border-gray-700 border-1 p-2 mt-1']) }}>
        {{ $content }}
    </div>
</div>
