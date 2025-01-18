<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name', 'Laravel') }}</title>
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <!-- Alpine.js -->
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <!-- Pusher -->
        <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
        <script>
            window.pusherKey = '{{ config('broadcasting.connections.pusher.key') }}';
            window.pusherCluster = '{{ config('broadcasting.connections.pusher.options.cluster') }}';
        </script>
        <!-- Chat Script -->
        <script src="{{ asset('js/chat.js') }}" defer></script>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')
            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif
            <!-- Page Content -->
            <main>
                {{ $slot ?? '' }}
            </main>

            @stack('modals')

            <x-chat-popup />

            @vite(['resources/css/app.css', 'resources/js/app.js'])
            @stack('scripts')
        </div>
    </body>
</html>