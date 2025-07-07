<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> {{ $title ?? 'linkmarks' }} </title>
    <link rel="icon" type="image/png" sizes="32x32"
        href="{{ asset('/favicon.svg') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600"
        rel="stylesheet" />

    <!-- Styles / Scripts -->
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    @vite(['resources/js/app.js'])

</head>

<body class="p-2">
    <x-navbar></x-navbar>

    <main>
        {{ $main }}
    </main>

    <x-html.alerts.alerts ref="alert" />
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
