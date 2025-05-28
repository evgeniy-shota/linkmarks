<header class="sticky top-0 mb-3 z-2">
    <div x-data class="navbar rounded-sm bg-gray-700 shadow-sm text-gray-100">
        <div class="navbar-start">
            <div class="dropdown me-1">
                <div tabindex="0" role="button"
                    class="btn bg-gray-500 border-gray-600 hover:border-gray-500 text-gray-100 shadow-md lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" />
                    </svg>
                    {{-- <x-html.icons.bookmarks-logo size="24" /> --}}
                </div>
                <ul tabindex="0"
                    class="menu menu-sm dropdown-content bg-gray-500 text-gray-100 rounded-box z-1 mt-3 w-52 p-2 shadow">
                    <x-navbar-menu-items />
                </ul>
            </div>
            <a class="text-xl cursor-pointer text-gray-300 hover:text-gray-100 transition"
                href="{{ route('home') }}">
                <div class="flex gap-1 justify-center items-center">
                    <x-html.icons.bookmarks-logo size="32" />
                    <div class="hidden sm:block">
                        Bookmarks
                    </div>
                </div>
            </a>
        </div>
        <div class="navbar-start hidden lg:flex">
            <ul class="menu menu-horizontal px-1">
                <x-navbar-menu-items />
            </ul>
        </div>
        <div class="navbar-end gap-1">
            @auth
                @if (Request::routeIs('home'))
                    <div class="dropdown dropdown-center">
                        <x-html.button-out-gray class="relative"
                            action='search.focus()'>
                            <x-html.icons.search />
                            <div class="hidden sm:block">
                                Search
                            </div>

                            <template x-if="$store.search.searchRequest.length>0">
                                <div aria-label="info"
                                    class="absolute top-0 right-0 w-[8px] h-[8px] bg-sky-400 rounded-sm">
                                </div>
                            </template>
                        </x-html.button-out-gray>

                        <div tabindex="0"
                            class="dropdown-content rounded bg-gray-600 p-2 pb-1 mt-1">
                            <div class="sm:w-[30vw] sm:max-h-[20vh] w-[70vw]">
                                <div class="flex gap-2 justify-center items-center">
                                    <x-html.formcontrols.input id="search"
                                        class="w-full"
                                        keyDown="validateInput($event)"
                                        input="searchInput($event)"
                                        maxLength="30" />
                                    <x-html.button-out-gray class="px-3"
                                        action="">
                                        <x-html.icons.search />
                                    </x-html.button-out-gray>

                                    <x-html.button-out-gray class="px-3"
                                        action="clearSearch()">
                                        <x-html.icons.x-lg />
                                    </x-html.button-out-gray>

                                </div>

                                <div class="text-sm text-gray-200">
                                    Enter at least 3 characters
                                </div>

                            </div>
                        </div>
                    </div>

                    <div class="dropdown dropdown-center">
                        <x-html.button-out-gray
                            action="getTags(Alpine.store('tags').setTags)">
                            <x-html.icons.funnel />
                            <div class="hidden sm:block">
                                Filter
                            </div>
                        </x-html.button-out-gray>

                        <div tabindex="0"
                            class="dropdown-content rounded bg-gray-600 border-gray-700 border-1 p-2 mt-1">
                            <div
                                class="mb-1 flex justify-center items-center gap-2 mb-2">
                                <x-html.button-out-blue class="btn-sm "
                                    action="">
                                    apply filter
                                </x-html.button-out-blue>
                                <x-html.button-out-gray class="btn-sm "
                                    action="">
                                    decline filter
                                </x-html.button-out-gray>
                            </div>
                            <div class="mb-1 flex justify-around items-center mb-2">
                                <x-html.button-out-gray
                                    action="$store.tags.setAllTagsState(null)"
                                    class="btn-sm px-2">
                                    <x-html.icons.square />
                                    <div>
                                        - not use
                                    </div>
                                </x-html.button-out-gray>

                                {{-- <div class="flex jusitfy-center items-center gap-1 rounded-sm bg-gray-500 hover:bg-gray-500/85 px-1 cursor-pointer"
                                    @@click="$store.tags.setAllTags(null)"
                                    title="not use tag">
                                    <x-html.icons.square />
                                    <div>
                                        - not use
                                    </div>
                                </div> --}}

                                <x-html.button-out-gray
                                    action="$store.tags.setAllTagsState(true)"
                                    class="btn-sm px-2">
                                    <x-html.icons.check-square />
                                    <div>
                                        - incl
                                    </div>
                                </x-html.button-out-gray>

                                {{-- <div class="flex jusitfy-center items-center gap-1 rounded-sm bg-gray-500 hover:bg-gray-500/85 px-1 cursor-pointer"
                                    @@click="$store.tags.setAllTags(true)"
                                    title="include tag">
                                    <x-html.icons.check-square />
                                    <div>
                                        - incl
                                    </div>
                                </div> --}}

                                <x-html.button-out-gray
                                    action="$store.tags.setAllTagsState(false)"
                                    class="btn-sm px-2">
                                    <x-html.icons.x-square />
                                    <div>
                                        - excl
                                    </div>
                                </x-html.button-out-gray>

                                {{-- <div class="flex jusitfy-center items-center gap-1 rounded-sm bg-gray-500 hover:bg-gray-500/85 px-1 cursor-pointer"
                                    @@click="$store.tags.setAllTags(false)"
                                    title="exclude tag">
                                    <x-html.icons.x-square />
                                    <div>
                                        - excl
                                    </div>
                                </div> --}}
                            </div>
                            <div class="border-b-2 border-gray-500 mb-2"></div>
                            <div @@click="clickOnTag($event)"
                                class="sm:w-[28vw] sm:max-h-[20vh] w-[70vw] overflow-y-auto grid grid-cols-3 gap-2">
                                <template x-for="(item, index) in $store.tags.tags">
                                    <x-html.tags.tag background="#3b82f6"
                                        state="item.state" xText="item.name"
                                        x-bind:data-tag="index">
                                    </x-html.tags.tag>
                                </template>
                            </div>
                        </div>
                    </div>


                    <x-html.button-out-gray action='openModal(folderModal)'>
                        <x-html.icons.folder-plus />
                        <div class="hidden sm:block">
                            Add folder
                        </div>
                    </x-html.button-out-gray>

                    <x-html.button-out-gray action="openModal(bookmarksModal)">
                        <x-html.icons.bookmarks-plus />
                        <div class="hidden sm:block">
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

            {{-- @guest
                <x-html.link link="{{ route('login') }}" :active="Request::routeIs('login') ? true : false">
                    Login
                </x-html.link>

                <x-html.link link="{{ route('registration.index') }}"
                    :active="Request::routeIs('registration.index')
                        ? true
                        : false">
                    Registration
                </x-html.link>
            @endguest --}}

        </div>
    </div>

    @if (Request::routeIs('home'))
        <div x-data class="mt-3">
            <x-html.breadcrumbs onclick="clickOnBreadcrumb"
                breadcrumbs="Alpine.store('contexts').breadcrumbs">
            </x-html.breadcrumbs>
        </div>
    @endif

</header>
