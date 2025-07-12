@props(['isBlocked' => false, 'timerValue' => '0'])
{{-- x-init=" {{ $isBlocked ? 'startTimer()' : null }}" --}}
<div class="relative">
    <div class="absolute w-full h-full z-2 bg-gray-500/70"
        x-bind:class="{ 'hidden': {{ $isBlocked }} }">
        <div class="flex justify-center items-center w-full h-full"
            x-text="{{ $timerValue }}"></div>
    </div>
    {{ $slot }}
</div>
