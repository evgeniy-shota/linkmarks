<header class="sticky top-0 mb-2 z-2">
    <div class="navbar bg-gray-600 shadow-sm text-gray-100">
        <div class="navbar-start">
            <div class="dropdown">
                <div tabindex="0" role="button"
                    class="btn bg-gray-500 border-gray-600 hover:border-gray-500 text-gray-100 shadow-md lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h8m-8 6h16" />
                    </svg>
                </div>
                <ul tabindex="0"
                    class="menu menu-sm dropdown-content bg-gray-500 text-gray-100 rounded-box z-1 mt-3 w-52 p-2 shadow">
                    <x-navbar-menu-items />
                </ul>
            </div>
            <a class="text-xl cursor-pointer">Bookmarks</a>
        </div>
        <div class="navbar-center hidden lg:flex">
            <ul class="menu menu-horizontal px-1">
                <x-navbar-menu-items />
            </ul>
        </div>
        <div class="navbar-end">
            @if (Request::routeIs('home'))
                <button onclick="bookmarksModal.showModal()"
                    class="btn bg-gray-500 border-gray-600 hover:border-gray-500 text-gray-100 shadow-md">
                    Add bookmark
                </button>
            @endif
        </div>
    </div>
</header>
