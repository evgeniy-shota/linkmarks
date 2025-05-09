@php
    $attributeName = 'element';
    $elementAttribute = "data-$attributeName";
    $elementAttributeType = "$elementAttribute-type";
    $elementAttributeAction = "$elementAttribute-action";
@endphp

<x-layout>
    <x-slot:main>
        <x-modal-window id="bookmarksModal" title="Add Bookmarks">
            <x-forms.bookmark-form>

            </x-forms.bookmark-form>
        </x-modal-window>

        {{-- <div class="text-gray-100">Bookmarks filter</div> --}}
        <div x-data>
            <button class="btn"
                @@click="console.log($store.contexts.data)">1</button>
            <x-html.button action="getContexts()">Contexts</x-html.button>
            <x-html.button action="getContexts()">Contexts</x-html.button>
            <x-html.breadcrumbs>
                <x-html.breadcrumbs-item />
            </x-html.breadcrumbs>
        </div>

        <div x-data @@click="clickOnElement"
            class="grid grid-cols-1 md:grid-cols-3 gap-2 md:gap-3">

            <template x-for="element in $store.contexts.data">
                <div>
                    <template x-if="'link' in element">
                        <x-bookmarks.bookmark-horizontal id="element.id"
                            name="element.name" link="element.link"
                            description="element.description"
                            thumbnail="element.thumbnail" :elementAttribute="$elementAttribute"
                            :elementAttributeAction="$elementAttributeAction" :elementAttributeType="$elementAttributeType" />
                    </template>

                    <template x-if="('link' in element)===false">
                        <x-folders.folder-horizontal id="element.id"
                            name="element.name" order="element.order"
                            parentContextId="2" :elementAttribute="$elementAttribute"
                            :elementAttributeAction="$elementAttributeAction" :elementAttributeType="$elementAttributeType" />
                    </template>
                </div>
            </template>


            {{-- @forelse ($bookmarks as $bookmark)
                <x-bookmarks.bookmark-horizontal :id="$bookmark['id']" :name="$bookmark['name']" :link="$bookmark['link']" :description="$bookmark['description']"
                    :thumbnail="$bookmark['thumbnail']" />
            @empty
                <div class="text-gray-100">
                    You don't have any bookmarks...
                </div>
            @endforelse --}}
        </div>

        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.store('contexts', {
                    previousContext: null,
                    currentContext: {{ $rootContext }},
                    orderNumber: null,
                    data: {{ Js::from($contexts) }},
                    breadcrumbs: [],

                    setData(data, previousContext, currentContext) {
                        this.previousContext = previousContext;
                        this.currentContext = currentContext;
                        this.orderNumber = null;
                        this.data = data;
                    },

                    clearData() {
                        this.previousContext = null;
                        this.currentContext = null;
                        this.orderNumber = null;
                        this.data = [];
                    },
                })
            });

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

                    clearThumbnail() {
                        this.thumbnail = null;
                        this.thumbnail_id = null;
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

            function clickOnElement(event) {
                let element = event.target.closest("[{{ $elementAttributeType }}]");

                if (!element) {
                    return
                }

                switch (element.dataset.elementType) {
                    case 'bookmark':
                        bookmarkClickHandler(event);
                        break;
                    case 'folder':
                        folderClickHandler(event)
                        break;
                }
            }

            function bookmarkClickHandler(event) {
                let target = event.target.closest("[{{ $elementAttributeAction }}]");

                if (target === null) {
                    console.log('...')
                    return
                }

                switch (target.dataset.{{ $attributeName }}Action) {
                    case 'edit':
                        editBookmark(target.dataset.{{ $attributeName }})
                        break;
                    case 'copy':
                        copyBookmarkLink(target.dataset.{{ $attributeName }});
                        break;
                    case 'open-new-tab':
                        openBookmarkInNewTab(target.dataset.{{ $attributeName }})
                        break;
                    case 'open-current-tab':
                        openBookmarkInCurrentTab(target.dataset.{{ $attributeName }})
                        break;
                    default:
                        break;
                }
            }

            function folderClickHandler(event) {
                let target = event.target.closest("[{{ $elementAttributeAction }}]");

                if (target === null) {
                    console.log('...')
                    return
                }

                switch (target.dataset.{{ $attributeName }}Action) {
                    case 'edit':
                        editFolder(target.dataset.{{ $attributeName }})
                        break;
                    case 'open':
                        openFolder(target.dataset.{{ $attributeName }})
                        break;
                    default:
                        break;
                }
            }

            function openFolder(id) {

                if (id == null) {
                    return
                }

                getContexts(id)
            }

            function editFolder(id) {

                if (id == null) {
                    return
                }

                getContext(id);
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
                    return res.data
                } else {
                    console.warn('get bookmark data fail...')
                    console.warn(response.status)
                    return null
                }
            }

            async function getContexts(id) {
                let response = await fetch('/contexts/' + id)

                if (response.ok) {
                    let res = await response.json()
                    return res.data
                } else {
                    console.warn('get bookmark data fail...')
                    console.warn(response.status)
                    return null
                }
            }

            async function getContext(id) {
                let response = await fetch('/context/' + id)

                if (response.ok) {
                    let res = await response.json()
                    return res.data
                } else {
                    console.warn('get bookmark data fail...')
                    console.warn(response.status)
                    return null
                }
            }
        </script>

    </x-slot:main>
</x-layout>
