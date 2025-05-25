<x-layout>
    <x-slot:main>
        <div
            class="w-full min-h-[80vh] flex flex-col justify-center items-center">
            <div class="text-gray-300">
                <div class="text-4xl font-semibold flex justify-center my-2">
                    403
                </div>
                <div class="my-1">
                    <x-captions.default class="mb-2">
                        {{ $exception?->getMessage() ?? 'Bad request' }}
                    </x-captions.default>
                    <div>{{ $message ?? '' }}</div>
                </div>
            </div>
        </div>
    </x-slot:main>
</x-layout>
