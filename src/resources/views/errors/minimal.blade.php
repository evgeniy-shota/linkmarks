<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ asset('/favicon.svg') }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        html {
            background-color: oklch(27.8% 0.033 256.848);
            scrollbar-gutter: stable;
        }

        /*! normalize.css v8.0.1 | MIT License | github.com/necolas/normalize.css */

        [hidden] {
            display: none
        }

        code {
            font-family: Menlo, Monaco, Consolas, Liberation Mono, Courier New, monospace
        }
    </style>

    <style>
        body {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        }
    </style>
</head>

<body>
    <div class="flex justify-center items-center text-2xl font-bold mt-4">
        <a href="{{ route('home') }}" class="text-gray-300 flex justify-center items-center">
            <x-logo></x-logo>
            <div>linkmarks</div>
        </a>
    </div>
    <div class="w-full min-h-[80vh] flex flex-col justify-center items-center">

        <div class="text-gray-300">
            <div class="text-5xl font-semibold flex justify-center my-2">
                @yield('code')
            </div>
            <div class="my-1">
                <x-captions.default class="mb-2">
                    @yield('message')
                </x-captions.default>
            </div>
        </div>
    </div>
</body>




{{-- <body class="antialiased">
    <div
        class="relative flex items-top justify-center min-h-screen bg-gray-100 dark:bg-gray-900 sm:items-center sm:pt-0">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center pt-8 sm:justify-start sm:pt-0">
                <div
                    class="px-4 text-lg text-gray-500 border-r border-gray-400 tracking-wider">
                    @yield('code')
                </div>

                <div
                    class="ml-4 text-lg text-gray-500 uppercase tracking-wider">
                    @yield('message')
                </div>
            </div>
        </div>
    </div>
</body> --}}

</html>
