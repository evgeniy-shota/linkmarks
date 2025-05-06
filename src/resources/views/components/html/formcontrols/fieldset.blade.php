@props(['title', 'titleSize' => 'text-base'])

<fieldset class="fieldset text-gray-100 max-w-100 flex-none">
    <legend class="fieldset-legend text-gray-100 {{ $titleSize }}">{{ $title }}</legend>
    {{ $field }}
    {{ $legend }}
</fieldset>
