@props(['text', 'action'])

<div 
    class="text-gray-300 hover:text-gray-100 transition duration-150">
    <x-html.icons.four-square />
    <div x-text={{ $text }}></div>
</div>
