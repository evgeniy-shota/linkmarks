<x-layout>
    <x-slot:main>
        <x-flex-container width="w-full xl:w-3/4 2xl:x-3/5">
            <div class="text-gray-100 mb-2 text-lg font-semibold">Welcome to linkmarks!</div>
            <div class="mb-2">
                Here you can save links to useful (and not so...) resources. Search, filtering by tags and grouping by
                folders are supported. Join us!
            </div>
            <x-captions.warning class="mb-1">
                The site is in <span class="font-semibold">testing</span>. Some features may not work as expected.
                We are working on it.
            </x-captions.warning>
            <div class="flex justify-center align-centerr">
                <img class="mask-t-from-90% mask-r-from-95% mask-b-from-90% mask-l-from-95%"
                    src="{{ url('storage/welcome_background_min.png') }}" alt="backgroud">
            </div>
        </x-flex-container>
    </x-slot:main>
</x-layout>
