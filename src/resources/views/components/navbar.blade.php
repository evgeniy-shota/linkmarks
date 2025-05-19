<header class="sticky top-0 mb-3 z-2">
    <div class="navbar rounded-sm bg-gray-700 shadow-sm text-gray-100">
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
        <div class="navbar-center hidden lg:flex">
            <ul class="menu menu-horizontal px-1">
                <x-navbar-menu-items />
            </ul>
        </div>
        <div class="navbar-end gap-1">
            @auth
                @if (Request::routeIs('home'))
                    <x-html.button action='openModal(folderModal)'>
                        <x-html.icons.folder-plus />
                        <div class="hidden sm:block">
                            Add folder
                        </div>
                    </x-html.button>

                    <x-html.button action='openModal(bookmarksModal)'>
                        <x-html.icons.bookmarks-plus />
                        <div class="hidden sm:block">
                            Add bookmark
                        </div>
                    </x-html.button>
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
</header>
