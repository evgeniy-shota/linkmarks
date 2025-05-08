@props(['active', 'disabled'])

<x-navbar-menu-item :route="route('home')">Home</x-navbar-menu-item>
@auth
    <x-navbar-menu-item :route="route('profile')">Profile</x-navbar-menu-item>
    {{-- <x-navbar-menu-item route='logout'>Logout</x-navbar-menu-item> --}}
    <form action="{{ route('logout') }}" method="post">
        @csrf
        <li>
            <button type="submit" class="cursor-pointer">Logout</button>
        </li>
    </form>
    {{-- <x-html.button class="btn-link" type="submit">
                Logout
            </x-html.button> --}}
@endauth
@guest
    <x-navbar-menu-item :route="route('login')">Login</x-navbar-menu-item>
    <x-navbar-menu-item :route="route('registration.index')">Registration</x-navbar-menu-item>
@endguest
