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

            @forelse ($bookmarks as $bookmark)
                <x-bookmarks.bookmark-horizontal :id="$bookmark['id']" :name="$bookmark['name']" :link="$bookmark['link']" :description="$bookmark['description']"
                    :thumbnail="$bookmark['thumbnail']" />
            @empty
                <div class="text-gray-100">
                    You don't have any bookmarks...
                </div>
            @endforelse
        </div>
        <x-flex-container>
            <x-html.formcontrols.input-file-drop-down id='thumbnail'>

            </x-html.formcontrols.input-file-drop-down>

        </x-flex-container>

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.store('bookmark', {
                    id: 122,
                    name: null,
                    link: null,
                    thumbnail: null,
                    thumbnail_id: null,
                    context_id: null,
                    order: null,

                    setData(data) {
                        this.id = data.id;
                        this.name = data.name;
                        this.link = data.link;
                        this.thumbnail = data.thumbnail;
                        this.thumbnail_id = data.thumbnail_id;
                        this.context_id = data.context_id;
                        this.order = data.order;
                    },
                    getData() {
                        return {
                            id: this.id,
                            name: this.name,
                            link: this.link,
                            thumbnail: this.thumbnail,
                            thumbnail_id: this.thumbnail_id,
                            context_id: 1,
                            order: 1,
                        }
                    },
                    clear() {
                        this.id = null;
                        this.name = null;
                        this.link = null;
                        this.thumbnail = null;
                        this.thumbnail_id = null;
                        this.context_id = null;
                        this.order = null;
                    }
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
                    case 'edit':
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

            async function editBookmark(id) {
                let bookmarkData = await getBookmarkData(id);
                console.log(bookmarkData);
                Alpine.store('bookmark').setData(bookmarkData);
                bookmarksModal.showModal()
            }

            async function getBookmarkData(id) {
                let response = await fetch('/bookmark/' + id)

                if (response.ok) {
                    let res = await response.json()
                    console.log(res.data)
                    return res.data
                } else {
                    console.warn('get bookmark data fail...')
                    console.warn(response.status)
                    return null
                }
            }

            // async function submitForm() {
            //     console.log('try submit');
            //     try {
            //         const response = await axios.post('/bookmarks',
            //             Alpine.store('bookmark').getData(),
            //         );
            //     } catch (error) {
            //         console.warn(error)
            //     }

            //     if (response) {
            //         console.log(response.data)
            //     }
            // }
        </script>

    </x-slot:main>
</x-layout>
