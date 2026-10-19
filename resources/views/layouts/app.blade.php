<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'POS Retail') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </head>
    <body class="font-sans antialiased text-gray-900 bg-gray-50 flex h-screen overflow-hidden">
        
        <!-- Sidebar -->
        @include('layouts.partials.sidebar')

        <!-- Main Content -->
        <div class="flex flex-col flex-1 w-full overflow-hidden">
            <!-- Top Header (Mobile hamburger + Breadcrumb maybe) -->
            <header class="flex items-center justify-between px-6 py-4 bg-white border-b border-gray-200 lg:hidden">
                <div class="text-xl font-bold text-gray-800">POS Retail</div>
                <!-- Add hamburger trigger here for mobile if needed -->
            </header>

            @isset($header)
                <header class="bg-white shadow-sm border-b border-gray-100 z-10">
                    <div class="px-8 py-5">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-gray-50 p-6 lg:p-8">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
