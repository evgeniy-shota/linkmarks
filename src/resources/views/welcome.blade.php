<x-layout>
    <x-slot:main>
        <x-flex-container width="w-full">
            <div class="text-gray-100 mb-2">Welcome!</div>
            <div>
                Here you can save links to useful (and not so...) resources.

                {{-- and
                we will try very hard not to lose
                them. --}}

                We support creating folders, searching and filtering bookmarks
                by tags.
                Join in!
            </div>
            <x-captions.warning>
                The site is in alpha testing, problems and errors are possible.
                We are working on it.
            </x-captions.warning>
        </x-flex-container>
    </x-slot:main>
</x-layout>
