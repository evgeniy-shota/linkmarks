{{-- @php
var_dump($bookmarks);
@endphp --}}
<x-layout>
    <x-slot:main>

        <x-modal-window id="bookmarksModal" title="Add Bookmarks">
            <x-forms.bookmark-form>

            </x-forms.bookmark-form>
        </x-modal-window>

        <div class="text-gray-100">Bookmarks filter</div>

        <div x-data @@click="clickOnBookmark" class="grid grid-cols-1 md:grid-cols-3 gap-2 md:gap-3">

            {{-- @php
                $bookmarks = [
                    [
                        'id' => 1,
                        'name' => 'Михеев и Павлов production',
                        'link' => 'https://www.youtube.com/',
                        'description' => 'Oficial youtube chanel',
                        'logo' => 'img/yt_favicon_32x32.png',
                    ],

                    [
                        'id' => 2,
                        'name' => 'Асафьев Стас',
                        'link' => 'www.youtube.com',
                        'description' => 'Oficial youtube chanel',
                        'logo' => 'img/yt_favicon_32x32.png',
                    ],

                    [
                        'id' => 3,
                        'name' => 'Михеев и Павлов production',
                        'link' => 'www.youtube.com',
                        'description' => 'Oficial youtube chanel',
                        'logo' => 'img/yt_favicon_32x32.png',
                    ],

                    [
                        'id' => 4,
                        'name' => 'Асафьев Стас',
                        'link' => 'www.youtube.com',
                        'description' => 'Oficial youtube chanel',
                        'logo' => 'img/yt_favicon_32x32.png',
                    ],

                    [
                        'id' => 5,
                        'name' => 'Михеев и Павлов production',
                        'link' => 'www.youtube.com',
                        'description' => 'Oficial youtube chanel',
                        'logo' => 'img/yt_favicon_32x32.png',
                    ],

                    [
                        'id' => 6,
                        'name' => 'Асафьев Стас',
                        'link' => 'www.youtube.com',
                        'description' => 'Oficial youtube chanel',
                        'logo' => 'img/yt_favicon_32x32.png',
                    ],
                ];
            @endphp --}}

            @forelse ($bookmarks as $bookmark)
                <x-bookmarks.bookmark-horizontal :id="$bookmark['id']" :name="$bookmark['name']" :link="$bookmark['link']" :description="$bookmark['description']"
                    :thumbnail="$bookmark['thumbnail']" />
            @empty
                <div class="text-gray-100">
                    You don't have any bookmarks...
                </div>
            @endforelse
        </div>

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.store('bookmark', {
                    id: 122,
                    name: null,
                    link: null,
                    thumbnail: null,
                })
            });

            function clickOnBookmark(event) {
                let target = event.target.closest('[data-bookmark-action]');

                if (target === null) {
                    console.log('...')
                    return
                }
                // console.log(target.dataset.bookmarkAction)

                switch (target.dataset.bookmarkAction) {
                    case 'settings':
                        editBookmark(target.dataset.bookmark)
                        break;
                    case 'copy':
                        copyBookmarkLink(target.dataset.bookmark);
                        break;
                    case 'open-new-tab':
                        openBookmarkInNewTab(target.dataset.bookmark)
                        break;
                    case 'open-current-tab':
                        openBookmarkInCurrentTab(target.dataset.bookmark)
                        break;
                    default:
                        break;
                }
            }

            function openBookmarkInNewTab(link) {
                window.open(link);
            }


            function openBookmarkInCurrentTab(link) {
                window.location.href = link;
            }

            function copyBookmarkLink(link) {
                navigator.clipboard.writeText(link).then(() => {
                    console.log('cpy sucess');
                }, (e) => {
                    console.log(e)
                })
            }

            function editBookmark(id) {
                Alpine.store('bookmark').id = id
                bookmarksModal.showModal()
            }

            async function getBookmarkData(id) {
                let response = await fetch('' + id)

                if (response.ok) {
                    let res = await response.json()
                } else {
                    console.warn('get bookmark data fail...')
                    console.warn(response.status)
                }
            }
        </script>

    </x-slot:main>
</x-layout>
