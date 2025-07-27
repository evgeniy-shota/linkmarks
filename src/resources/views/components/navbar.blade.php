@php
    $routeNotHome = Route::currentRouteName() != 'home';
@endphp

<header class="sticky top-0 mt-2 mb-4 z-2">
    <div x-data
        class="navbar rounded-t-sm bg-gray-700 shadow-sm text-gray-100 {{ $routeNotHome ? 'rounded-b-sm' : '' }}">
        <div class="navbar-start">
            <div class="dropdown me-1">
                <div tabindex="0" role="button"
                    class="btn bg-gray-500 border-gray-600 hover:border-gray-500 text-gray-100 shadow-md ">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h8m-8 6h16" />
                    </svg>
                    {{-- <x-html.icons.bookmarks-logo size="24" /> --}}
                </div>
                <ul tabindex="0"
                    class="menu menu-sm dropdown-content bg-gray-500 text-gray-100 rounded-box z-1 mt-3 w-52 p-2 shadow">
                    <x-navbar-menu-items />
                </ul>
            </div>
            <a class="text-xl cursor-pointer text-gray-300 hover:text-gray-100 transition" href="{{ route('home') }}">
                <div class="flex gap-1 justify-center items-center">
                    <x-html.icons.bookmarks-logo size="32" />
                    <div class="hidden sm:block">
                        linkmarks
                    </div>

                    <div
                        class="text-sm font-semibold bg-gray-200 text-gray-800 rounded-sm px-1 py-0">
                        v0.2
                    </div>
                </div>
            </a>
        </div>

        <div class="navbar-end gap-0 sm:gap-1">
            @auth
                @if (Request::routeIs('home'))
                    <div class="dropdown dropdown-bottom sm:dropdown-center ">
                        <x-html.button-out-gray class="relative" action='search.focus()'>
                            <x-html.icons.search />
                            <div class="hidden md:block">
                                Search
                            </div>

                            <template x-if="$store.search.searchRequest.length>0">
                                <div aria-label="info" class="absolute top-0 right-0 w-[8px] h-[8px] bg-sky-400 rounded-sm">
                                </div>
                            </template>
                        </x-html.button-out-gray>

                        <div tabindex="0" class="dropdown-content rounded bg-gray-600 p-2 pb-1 mt-1">
                            <div class="w-[60vw] sm:w-[40vw] md:w-[30vw] sm:max-h-[20vh] ">
                                <div class="flex gap-1 justify-center items-center">
                                    <x-html.formcontrols.input id="search" class="w-full" keyDown="validateInput($event)"
                                        input="searchInput($event,500)" maxLength="30" />
                                    <x-html.button-out-gray class="px-3"
                                        action="()=>search.value.length>1 && startSearch(search.value)">
                                        <x-html.icons.search />
                                    </x-html.button-out-gray>

                                    <x-html.button-out-gray class="px-3" action="clearSearch()">
                                        <x-html.icons.x-lg />
                                    </x-html.button-out-gray>

                                </div>

                                <div class="text-sm text-gray-200">
                                    Enter at least 2 characters
                                </div>

                            </div>
                        </div>
                    </div>

                    {{-- Filter --}}
                    <x-filter />

                    {{-- Tags --}}
                    <x-tags />

                    {{-- Add folder --}}
                    <x-html.button-out-gray action='openModal(folderModal)'>
                        <x-html.icons.folder-plus />
                        <div class="hidden md:block">
                            Add folder
                        </div>
                    </x-html.button-out-gray>

                    {{-- Add bookmarks --}}
                    <x-html.button-out-gray action="openModal(bookmarksModal)">
                        <x-html.icons.bookmarks-plus />
                        <div class="hidden md:block">
                            Add bookmark
                        </div>
                    </x-html.button-out-gray>
                @else
                    <form action="{{ route('logout') }}" method="post">
                        @csrf
                        <x-html.button type="submit" :active="request()->is('logout') ? true : false">
                            Logout
                        </x-html.button>
                    </form>
                @endif
            @endauth

        </div>
    </div>

    @if (Request::routeIs('home'))
        <div x-data class="border-t-1 border-b-1 rounded-sm border-gray-800">
            <x-html.breadcrumbs onclick="clickOnBreadcrumb" breadcrumbs="Alpine.store('contexts').breadcrumbs">
            </x-html.breadcrumbs>
        </div>
    @endif

</header>
