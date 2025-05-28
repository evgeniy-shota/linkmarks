@php
    $attributeName = 'element';
    $elementAttribute = "data-$attributeName";
    $elementAttributeType = "$elementAttribute-type";
    $elementAttributeAction = "$elementAttribute-action";
@endphp

<x-layout>
    <x-slot:main>

        {{-- Modal window with Bookmarks form --}}
        <x-modal-window id="bookmarksModal"
            title="$store.bookmark.id===null?'Add Bookmark':'Edit bookmark'"
            closeButtonAction="closeModal()">
            <x-forms.bookmark-form modalId="bookmarksModal"
                canDeleted="$store.bookmark.id!==null?true:false">
            </x-forms.bookmark-form>
        </x-modal-window>

        {{-- Modal window with Folder form --}}
        <x-modal-window id="folderModal"
            title="$store.context.id===null?'Add Folder':'Edit folder'"
            closeButtonAction="closeModal()">
            <x-forms.folder-form modalId="folderModal"
                canDeleted="$store.context.id!==null?true:false">
            </x-forms.folder-form>
        </x-modal-window>

        {{-- Tool bar --}}
        {{-- <div class="text-gray-100">Bookmarks filter</div> --}}
        {{-- <div x-data
            class="flex justify-between items-center gap-1 mb-3 sticky z-5 top-16"> --}}
        {{-- <x-html.button
                action="Alpine.store('alerts').addAlert('test message')">Contexts</x-html.button>
            <x-html.button
                action="Alpine.store('alerts').getAlert('test message')">Contexts</x-html.button> --}}

        {{-- <x-html.breadcrumbs onclick="clickOnBreadcrumb"
                breadcrumbs="Alpine.store('contexts').breadcrumbs">
            </x-html.breadcrumbs> --}}

        {{-- <x-html.button-out-gray action='openModal(folderModal)'>
                <x-html.icons.search />
                <div class="hidden sm:block">
                    Search
                </div>
            </x-html.button-out-gray>

            <x-html.button-out-gray action='openModal(folderModal)'>
                <x-html.icons.funnel />
                <div class="hidden sm:block">
                    Filter
                </div>
            </x-html.button-out-gray> --}}
        {{-- </div> --}}


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

                    <x-html.button-out-gray action="openModal(folderModal)">
                        <x-html.icons.folder-plus />
                        Add folder
                    </x-html.button-out-gray>

                    <x-html.button-out-gray action="openModal(bookmarksModal)">
                        <x-html.icons.bookmarks-plus />
                        Add bookmark
                    </x-html.button-out-gray>

                </div>
            </x-horizontal-container>
        </div>

        <script>
            let searchTimeId = null;

            document.addEventListener('alpine:init', () => Alpine.store('contexts')
                .initial(
                    {{ Js::from($rootContext) }},
                    {{ Js::from($rootContext) }},
                    {{ Js::from($contexts) }},
                    {{ Js::from($rootContext) }},
                ));

            document.addEventListener('alpine:init', () => addAlerts());

            function validateInput(e) {
                // 8 Backspace, 9 Tab, 32 ' ', 46 Delete, 48-57 numbers, 65-90 a-z, 173 '-', 222 "'",
                // 188 ',', 190 '.', 36 home, 35 end, 37-39 arrows,
                if (!(e.keyCode === 8 || e.keyCode === 9 || e.keyCode === 46 ||
                        e.keyCode === 32 || e.keyCode === 173 || e.keyCode === 222 ||
                        e.keyCode === 188 || e.keyCode === 190 ||
                        (e.keyCode >= 35 && e.keyCode <= 39) ||
                        (e.keyCode >= 48 && e.keyCode <= 57) ||
                        (e.keyCode >= 65 && e.keyCode <= 90))) {
                    // console.log('bad key');
                    e.preventDefault()
                    return
                }

                if ((e.keyCode >= 35 && e.keyCode <= 39) || e.keyCode === 9) {
                    // console.log('just end')
                    return;
                }

                if (!validateText(e.key)) {
                    // console.log('not valid')
                    e.preventDefault()
                    return
                }
            }

            function searchInput(e, timerDelayMs = 1200) {

                if (searchTimeId !== null) {
                    clearTimeout(searchTimeId)
                    searchTimeId = null
                }

                if (e.target.value.length < 3) {
                    // console.log('less 3');
                    return;
                }

                let search = e.target.value;

                searchTimeId = setTimeout(async () => {
                    let response = await searchRequest(search);
                    clearSearchResult()
                    Alpine.store('search').searchRequest = search
                    Alpine.store('search').searchResult = response
                    setSearchContext(response, search)
                    searchTimeId = null;
                }, timerDelayMs);

            }

            function setSearchContext(searchResult, search) {

                if (!searchResult) {
                    return;
                }

                let previousContext = Alpine.store('contexts').currentContext
                let context = {
                    name: 'Search result',
                    type: 'search',
                    search: search,
                }

                Alpine.store('contexts').setData(
                    searchResult,
                    previousContext,
                    context,
                    searchResult.lenght > 0 ? searchResult[searchResult.length - 1]
                    .order : 0)
                Alpine.store('contexts').pushToBreadcrumbs(context)
            }

            function clearSearch() {
                search.value = "";
                clearSearchResult()
                openFolder(Alpine.store('contexts').breadcrumbs[
                    Alpine.store('contexts').breadcrumbs.length - 1
                ]);
            }

            function clearSearchResult(open) {

                if (Alpine.store('search').searchRequest.length == 0) {
                    return;
                }

                Alpine.store('search').clear();
                let searchBreadcrumbsIndex = Alpine.store('contexts').breadcrumbs
                    .findIndex((context) => context.type == 'search');

                searchBreadcrumbsIndex = searchBreadcrumbsIndex == -1 ?
                    Alpine.store('contexts').breadcrumbs.length - 1 :
                    searchBreadcrumbsIndex - 1;

                Alpine.store('contexts').spliceBreadcrumbs(searchBreadcrumbsIndex);

            }

            function addAlerts() {
                @if (session('verificationStatus'))
                    Alpine.store('alerts')
                        .addAlert("{{ session('verificationStatus') }}", 'success');
                @endif
            }

            function clickOnTag(e) {

                let tag = e.target.closest('[data-tag]');

                if (!tag) {
                    return
                }

                console.log(tag.dataset.tag)
                Alpine.store('tags').toggleTag(tag.dataset.tag)
            }

            async function clickOnBreadcrumb(event) {
                let breadcrumb = event.target.closest('[data-breadcrumb]');
                let breadcrumbIndex = breadcrumb.dataset.breadcrumb
                let context = Alpine.store('contexts').breadcrumbs[breadcrumbIndex]

                if (context.type == 'search') {
                    setSearchContext(await searchRequest(context.search), context
                        .search)
                } else {
                    openFolder(context)
                }

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
                        Alpine.store('bookmark').indexInContexts = index
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

            async function getTags(callback = null) {
                let url = '/tags';

                let response = await getRequest(url)

                Alpine.store('tags').setTags(await response)

                return await response;
            }

            async function getRequest(url, consoleWarnTitle = null) {

                let response = await fetch(url)

                if (response.ok) {
                    let res = await response.json()
                    return res.data
                } else {
                    console.warn(
                        consoleWarnTitle ?? "Get request to " + url + " fail")
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

            async function updateRequest(url, data) {
                let response = null;

                try {
                    response = await axios.post(
                        url, {
                            ...data,
                            _method: 'PUT',
                        }, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }
                    );
                } catch (error) {
                    console.warn('Update fail...')
                    console.warn(error)
                    return false
                }

                if (response) {
                    return response.data.data
                }

                return false
            }

            async function deleteRequest(url, id) {
                let response = null;

                try {
                    response = axios.delete(url + id)
                } catch (error) {
                    console.warn('Delete bookmark fail')
                    console.warn(error);
                    return false
                }

                if (response) {
                    return true
                }

                return false
            }

            async function searchRequest(search) {
                let response = null;

                try {
                    response = await axios.get('/search', {
                        params: {
                            search: search
                        }
                    })
                } catch (error) {
                    console.warn('search error');
                    console.warn(error);
                    Alpine.store('alerts').addAlert('Search fail, try later');
                    return false;
                }

                if (response) {
                    return response.data.data
                }

                Alpine.store('alerts').addAlert('Search fail, try later');
                return false;
            }

            function objToFormdata(obj) {
                let formdata = new FormData();

                for (let data of Object.entries(obj)) {
                    let value = typeof data[1] !== 'object' ? String(data[1]) : data[1];

                    formdata.append(data[0], data[1]);
                }

                return formdata;
            }

            function validateText(text) {
                let regx = /[\d\w-',.]/;

                if (regx.test(text)) {
                    return true
                }

                return false
            }
        </script>

    </x-slot:main>
</x-layout>
