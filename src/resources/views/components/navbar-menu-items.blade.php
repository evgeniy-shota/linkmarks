@props(['active', 'disabled'])

<x-navbar-menu-item route='home.index'>Home</x-navbar-menu-item>
@auth
    <x-navbar-menu-item route='profile'>Profile</x-navbar-menu-item>
    <x-navbar-menu-item route='logout'>Logout</x-navbar-menu-item>
@endauth
@guest
    <x-navbar-menu-item route='login'>Login</x-navbar-menu-item>
    <x-navbar-menu-item route='registration.index'>Registration</x-navbar-menu-item>
@endguest
