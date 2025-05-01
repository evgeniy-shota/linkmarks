<x-layout>
    <x-slot:main>

        <x-modal-window id="bookmarksModal" title="Add Bookmarks">
            <x-forms.bookmark-form>

            </x-forms.bookmark-form>
        </x-modal-window>

        <div class="text-gray-100">Bookmarks filter</div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-2 md:gap-3">

            @php
            $bookmarks = [
            ['name'=>'Михеев и Павлов production',
            'link'=>'www.youtube.com',
            'description'=>'Oficial youtube chanel',
            'logo'=>'img/yt_favicon_32x32.png'],

            ['name'=>'Асафьев Стас',
            'link'=>'www.youtube.com',
            'description'=>'Oficial youtube chanel',
            'logo'=>'img/yt_favicon_32x32.png'],

            ['name'=>'Михеев и Павлов production',
            'link'=>'www.youtube.com',
            'description'=>'Oficial youtube chanel',
            'logo'=>'img/yt_favicon_32x32.png'],

            ['name'=>'Асафьев Стас',
            'link'=>'www.youtube.com',
            'description'=>'Oficial youtube chanel',
            'logo'=>'img/yt_favicon_32x32.png'],

            ['name'=>'Михеев и Павлов production',
            'link'=>'www.youtube.com',
            'description'=>'Oficial youtube chanel',
            'logo'=>'img/yt_favicon_32x32.png'],

            ['name'=>'Асафьев Стас',
            'link'=>'www.youtube.com',
            'description'=>'Oficial youtube chanel',
            'logo'=>'img/yt_favicon_32x32.png'],
            ];
            @endphp

            @forelse ($bookmarks as $bookmark)
            <x-bookmarks.bookmark-horizontal
                :name='$bookmark["name"]'
                :link='$bookmark["link"]'
                :description='$bookmark["description"]'
                :logo='$bookmark["logo"]' />
            @empty
            <div class="text-gray-100">
                You don't have any bookmarks...
            </div>
            @endforelse
        </div>
    </x-slot:main>
</x-layout>