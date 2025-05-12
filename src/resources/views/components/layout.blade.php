<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> {{ $title ?? 'Bookmarks' }} </title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600"
        rel="stylesheet" />

    <!-- Styles / Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>

<body class="p-2">
    <!-- @csrf -->
    <x-navbar></x-navbar>

    <main>

        {{ $main }}

    </main>
</body>

<script>
    // override default browser behavior for drag and drop events
    ["dragover", "drop"].forEach(function(event) {
        document.addEventListener(event, function(evt) {
            evt.preventDefault()
            return false
        })
    })
</script>

</html>
