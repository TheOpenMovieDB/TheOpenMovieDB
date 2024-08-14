<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('partials._head')
</head>
<body class="font-sans text-gray-900 bg-gray-100 dark:bg-gray-900 antialiased">
@include('partials._header')
<main class="flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
    @yield('content')
</main>

<footer>
    {{--  todo  --}}
</footer>
@livewireScriptConfig
</body>
</html>
