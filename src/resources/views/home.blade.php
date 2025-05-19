@php
    $attributeName = 'element';
    $elementAttribute = "data-$attributeName";
    $elementAttributeType = "$elementAttribute-type";
    $elementAttributeAction = "$elementAttribute-action";
@endphp

<x-layout>
    <x-slot:main>

        <x-modal-window id="bookmarksModal"
            title="$store.bookmark.id===null?'Add Bookmark':'Edit bookmark'"
            closeButtonAction="closeModal()">
            <x-forms.bookmark-form modalId="bookmarksModal"
                canDeleted="$store.bookmark.id!==null?true:false">
            </x-forms.bookmark-form>
        </x-modal-window>

        <x-modal-window id="folderModal"
            title="$store.context.id===null?'Add Folder':'Edit folder'"
            closeButtonAction="closeModal()">
            <x-forms.folder-form modalId="folderModal"
                canDeleted="$store.context.id!==null?true:false">

            </x-forms.folder-form>
        </x-modal-window>

        {{-- <div class="text-gray-100">Bookmarks filter</div> --}}
        <div x-data class="mb-3 sticky z-2 top-16">
            {{-- <x-html.button
                action="Alpine.store('alerts').addAlert('test message')">Contexts</x-html.button>
            <x-html.button
                action="Alpine.store('alerts').getAlert('test message')">Contexts</x-html.button> --}}

            <x-html.breadcrumbs onclick="clickOnBreadcrumb"
                breadcrumbs="Alpine.store('contexts').breadcrumbs">

            </x-html.breadcrumbs>
        </div>

        <div x-data @@click="clickOnElement"
            class="grid grid-cols-1 md:grid-cols-3 gap-3 md:gap-4">

            <template x-for="(element, index) in $store.contexts.data"
                ::key="element.id">
                <div>
                    <template x-if="'link' in element">
                        <x-bookmarks.bookmark-horizontal id="index"
                            name="element.name" link="element.link"
                            description="element.description"
                            thumbnail="element.thumbnail" :elementAttribute="$elementAttribute"
                            :elementAttributeAction="$elementAttributeAction" :elementAttributeType="$elementAttributeType" />
                    </template>

                    <template x-if="('link' in element)===false">
                        <x-folders.folder-horizontal id="index"
                            name="element.name" order="element.order"
                            parentContextId="2" :elementAttribute="$elementAttribute"
                            :elementAttributeAction="$elementAttributeAction" :elementAttributeType="$elementAttributeType" />
                    </template>
                </div>
            </template>

            <x-horizontal-container class="px-2 py-2">
                <div
                    class="flex justify-around items-center border-2 border-dashed border-gray-400 rounded-sm w-full h-full p-3">
                    <x-html.button action="openModal(folderModal)">
                        <x-html.icons.folder-plus />
                        Add folder
                    </x-html.button>
                    <x-html.button action="openModal(bookmarksModal)">
                        <x-html.icons.bookmarks-plus />
                        Add bookmark
                    </x-html.button>
                </div>
            </x-horizontal-container>
        </div>


        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.store('contexts', {
                    rootContext: {{ Js::from($rootContext) }},
                    previousContext: null,
                    currentContext: {{ Js::from($rootContext) }},
                    orderNumber: null,
                    data: {{ Js::from($contexts) }},
                    breadcrumbs: [{{ Js::from($rootContext) }}],

                    getLastOrder() {
                        return this.data.length > 0 ? this.data[this
                            .data.length - 1].order : 0;
                    },

                    pushToBreadcrumbs(data) {
                        this.breadcrumbs.push(data);
                    },

                    spliceBreadcrumbs(
                        index,
                        count = this.breadcrumbs.length) {
                        console.log(index + 1)
                        this.breadcrumbs.splice(
                            Number(index) + 1,
                            count
                        );

                    },

                    setData(data, previousContext, currentContext,
                        orderNumber) {
                        this.previousContext = previousContext;
                        this.currentContext = currentContext;
                        this.orderNumber = orderNumber;
                        this.data = data;
                    },

                    clearData() {
                        this.previousContext = null;
                        this.currentContext = this.rootContext;
                        this.orderNumber = null;
                        this.data = [];
                        this.breadcrumbs = [];
                    },
                })
            });

            document.addEventListener('alpine:init', () => {
                Alpine.store('bookmark', {
                    id: null,
                    name: null,
                    link: null,
                    thumbnail: null,
                    thumbnail_id: null,
                    context_id: null,
                    indexInContext: null,
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
                            context_id: Alpine.store('contexts')
                                .currentContext.id,
                            order: this.order,
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
                        this.indexInContext = null
                    }
                })
            });

            document.addEventListener('alpine:init', () => {
                Alpine.store('context', {
                    id: null,
                    name: null,
                    thumbnail: null,
                    thumbnail_id: null,
                    parent_context_id: null,
                    indexInContexts: null,
                    order: null,

                    setData(data) {
                        this.id = data.id;
                        this.name = data.name;
                        this.thumbnail = data.thumbnail;
                        this.thumbnail_id = data.thumbnail_id;
                        this.parent_context_id = data
                            .parent_context_id;
                        this.order = data.order;
                    },

                    getData() {
                        return {
                            id: this.id,
                            name: this.name,
                            link: this.link,
                            thumbnail: this.thumbnail,
                            thumbnail_id: this.thumbnail_id,
                            parent_context_id: Alpine.store('contexts')
                                .currentContext.id,
                            order: this.order,
                        }
                    },

                    clearThumbnail() {
                        this.thumbnail = null;
                        this.thumbnail_id = null;
                    },

                    clear() {
                        this.id = null;
                        this.name = null;
                        this.thumbnail = null;
                        this.thumbnail_id = null;
                        this.parent_context_id = null;
                        this.order = null;
                        this.indexInContexts = null;
                    }
                })
            });

            function clickOnBreadcrumb(event) {
                let breadcrumb = event.target.closest('[data-breadcrumb]');
                console.log('breadcrumb: ')
                console.log(breadcrumb)
                let breadcrumbIndex = breadcrumb.dataset.breadcrumb
                let context = Alpine.store('contexts').breadcrumbs[breadcrumbIndex]
                openFolder(context)
                Alpine.store('contexts').spliceBreadcrumbs(breadcrumbIndex)
            }

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
                        let index = target.dataset.{{ $attributeName }};
                        Alpine.store('bookmark').indexInContext = index
                        editBookmark(
                            Alpine.store('contexts').data[index])
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

                let index = target.dataset.{{ $attributeName }}
                Alpine.store('context').indexInContexts = index
                let context = Alpine.store('contexts').data[index];
                switch (target.dataset.{{ $attributeName }}Action) {
                    case 'edit':
                        editFolder(context)
                        break;

                    case 'open':
                        openFolder(context)
                        Alpine.store('contexts').pushToBreadcrumbs(context)
                        break;

                    default:
                        break;
                }
            }

            async function openFolder(context) {
                console.log(context)

                if (context.id == null) {
                    return
                }

                let previousContext = Alpine.store('contexts').currentContext
                let data = await getContexts(context.id)

                Alpine.store('contexts').setData(
                    data,
                    previousContext,
                    context,
                    data.lenght > 0 ? data[data.lenght - 1].order : 0)
            }

            async function editFolder(data) {
                let context = await getContext(data.id);
                Alpine.store('context').setData(context);
                folderModal.showModal()
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

            async function editBookmark(data) {
                let bookmarkData = await getBookmarkData(data.id);
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

            function openModal(id) {
                let lastOrder = Alpine.store('contexts').getLastOrder();
                Alpine.store('bookmark').order = lastOrder;
                Alpine.store('context').order = lastOrder;
                id.show()
            }

            function closeModal() {
                Alpine.store('bookmark').clear()
                Alpine.store('context').clear()
                // console.log(Alpine.store('fileInput'))
                Alpine.store('fileInput').clearData()
            }

            async function submitForm(data, url, callback = null) {
                console.log('try submit');
                console.log(data)
                let response = null;

                try {
                    response = await axios.post(
                        url,
                        data, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }
                    );
                } catch (error) {
                    console.warn(error);
                    return false
                }

                if (response) {
                    console.log(response.data)
                    if (callback !== null) {
                        callback(response.data.data)
                    }
                    return true;
                }
            }

            async function deleteRequest(url, id) {
                let response = null;

                try {
                    response = axios.delete(url + id)
                } catch (error) {
                    console.log('Delete bookmark fail')
                    console.log(error);
                    return false
                }

                if (response) {
                    return true
                }

                return false
            }
        </script>

    </x-slot:main>
</x-layout>
