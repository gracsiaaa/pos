<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - POS Retail</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
</head>
<body class="font-sans antialiased text-gray-900 bg-gray-50 flex h-screen overflow-hidden">
    <!-- Panel Kiri: Branding -->
    <div class="hidden lg:flex lg:w-1/2 bg-gray-900 relative overflow-hidden flex-col justify-center px-12 xl:px-24">
        <!-- Abstract pattern background -->
        <div class="absolute inset-0 opacity-10">
            <svg class="h-full w-full" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <pattern id="grid" width="40" height="40" patternUnits="userSpaceOnUse">
                        <path d="M0 40V0H40" fill="none" stroke="currentColor" stroke-width="1"/>
                    </pattern>
                </defs>
                <rect width="100%" height="100%" fill="url(#grid)" class="text-white"/>
            </svg>
        </div>
        
        <div class="relative z-10 text-white">
            <div class="flex items-center gap-3 mb-8">
                <div class="p-3 bg-primary-600 rounded-xl shadow-lg shadow-primary-500/30">
                    <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <h1 class="text-4xl font-bold tracking-wider uppercase">POS Retail</h1>
            </div>
            
            <h2 class="text-3xl font-bold mb-4">Kelola toko Anda <br>dengan lebih mudah.</h2>
            <p class="text-gray-400 text-lg max-w-md leading-relaxed">
                Sistem Point of Sales modern yang cepat, aman, dan mudah digunakan untuk manajemen stok dan transaksi harian toko Anda.
            </p>
            
            <div class="mt-12 flex gap-4">
                <div class="px-4 py-2 rounded-lg bg-gray-800/50 border border-gray-700/50 backdrop-blur-sm flex items-center gap-2 text-sm font-medium">
                    <div class="w-2 h-2 rounded-full bg-emerald-500"></div> Sistem Aktif
                </div>
            </div>
        </div>
    </div>

    <!-- Panel Kanan: Login Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 lg:p-12">
        <div class="w-full max-w-md">
            <!-- Mobile Logo -->
            <div class="flex items-center justify-center gap-2 mb-8 lg:hidden text-gray-900">
                <div class="p-2 bg-primary-600 rounded-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
                <span class="text-2xl font-bold uppercase tracking-wider">POS Retail</span>
            </div>

            <div class="text-center lg:text-left mb-8">
                <h2 class="text-3xl font-bold text-gray-900 mb-2">Selamat Datang</h2>
                <p class="text-gray-500">Silakan masuk menggunakan akun Anda.</p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />
            
            @if(session('error'))
                <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-4 rounded-r-lg" role="alert">
                    <p class="font-medium">{{ session('error') }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email / Username</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path></svg>
                        </div>
                        <input id="email" class="block w-full pl-10 px-4 py-3 rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 transition-colors" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="admin@pos.com" />
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <input id="password" class="block w-full pl-10 px-4 py-3 rounded-xl border-gray-300 shadow-sm focus:border-primary-500 focus:ring focus:ring-primary-500 focus:ring-opacity-50 transition-colors" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <label for="remember_me" class="inline-flex items-center cursor-pointer">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500 w-5 h-5" name="remember">
                        <span class="ml-2 text-sm text-gray-600 select-none">Ingat saya</span>
                    </label>
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                        Masuk Sistem
                    </button>
                </div>
            </form>
            
            <div class="mt-8 text-center text-sm text-gray-400">
                &copy; {{ date('Y') }} POS Retail System.
            </div>
        </div>
    </div>
</body>
</html>
