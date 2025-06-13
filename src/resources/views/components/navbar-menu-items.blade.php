@props(['active', 'disabled'])

<x-navbar-menu-item :route="route('home')" :isActive="route('home') === url()->current() ||
    route('welcome') === url()->current()">
    Home
</x-navbar-menu-item>
@auth
    <x-navbar-menu-item :route="route('profile')" :isActive="route('profile') === url()->current()">
        Profile
    </x-navbar-menu-item>
    {{-- <x-navbar-menu-item route='logout'>Logout</x-navbar-menu-item> --}}
    <form action="{{ route('logout') }}" method="post">
        @csrf
        <li>
            <button type="submit" class="cursor-pointer text-base">Logout</button>
        </li>
    </form>
    {{-- <x-html.button class="btn-link" type="submit">
                Logout
            </x-html.button> --}}
@endauth
@guest
    <x-navbar-menu-item :route="route('login')" :isActive="route('login') === url()->current()">
        Login
    </x-navbar-menu-item>
    <x-navbar-menu-item :route="route('registration.index')" :isActive="route('registration.index') === url()->current()">
        Registration
    </x-navbar-menu-item>
@endguest
