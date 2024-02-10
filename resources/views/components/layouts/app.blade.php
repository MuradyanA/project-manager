<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    @livewireStyles
    @livewireScripts
    <title>{{ $title ?? 'Scrum' }}</title>
</head>

<body
    class="relative min-h-screen bg-dots-darker bg-center bg-[#7eb191] dark:bg-dots-lighter dark:bg-[#7eb191] selection:bg-blue-300 selection:text-white">
    @if (!request()->is('/'))
        <x-Navbar />
    @endif
    
    {{ $slot }}
</body>

</html>
