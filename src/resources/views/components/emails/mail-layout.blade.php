<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> {{ $title ?? 'linkmarks' }} </title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600"
        rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="p-2">

    <main class="p-2">
        <div class="flex justify-center items-center">
            <div>
                <x-logo width="40" />
            </div>
            <div class="text-xl font-bold">linkmarks</div>
        </div>
        {{ $main }}

    </main>
</body>

</html>
