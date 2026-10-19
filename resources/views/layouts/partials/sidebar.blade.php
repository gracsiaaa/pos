<aside class="flex flex-col w-64 h-screen px-4 py-8 overflow-y-auto bg-gray-900 border-r border-gray-800 rtl:border-r-0 rtl:border-l">
    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-2 text-white hover:text-gray-300">
        <svg class="w-8 h-8 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
        </svg>
        <span class="text-xl font-bold tracking-wider uppercase">POS Retail</span>
    </a>

    <div class="flex flex-col justify-between flex-1 mt-6">
        <nav class="space-y-2">
            
            <a class="flex items-center px-4 py-2.5 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('dashboard') ? 'bg-gray-800 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}" href="{{ route('dashboard') }}">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                Dashboard
            </a>

            @if(in_array(Auth::user()->role->name, [\App\Enums\RoleEnum::ADMIN->value, \App\Enums\RoleEnum::OWNER->value]))
            <p class="px-4 pt-4 pb-2 text-xs font-semibold tracking-wider text-gray-500 uppercase">Master Data</p>
            
            <a class="flex items-center px-4 py-2.5 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('admin.products.*') ? 'bg-gray-800 text-white border-l-4 border-primary-500' : 'text-gray-400 hover:bg-gray-800 hover:text-white group' }}" href="{{ route('admin.products.index') }}">
                <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                Produk
            </a>
            
            <a class="flex items-center px-4 py-2.5 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('admin.categories.*') ? 'bg-gray-800 text-white border-l-4 border-primary-500' : 'text-gray-400 hover:bg-gray-800 hover:text-white group' }}" href="{{ route('admin.categories.index') }}">
                <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                Kategori
            </a>
            
            <a class="flex items-center px-4 py-2.5 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('admin.suppliers.*') ? 'bg-gray-800 text-white border-l-4 border-primary-500' : 'text-gray-400 hover:bg-gray-800 hover:text-white group' }}" href="{{ route('admin.suppliers.index') }}">
                <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"></path></svg>
                Supplier
            </a>
            @endif

            @if(in_array(Auth::user()->role->name, [\App\Enums\RoleEnum::ADMIN->value, \App\Enums\RoleEnum::CASHIER->value]))
            <p class="px-4 pt-4 pb-2 text-xs font-semibold tracking-wider text-gray-500 uppercase">Operasional</p>
            
            <a class="flex items-center px-4 py-2.5 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('admin.transactions.*') ? 'bg-gray-800 text-white border-l-4 border-primary-500' : 'text-gray-400 hover:bg-gray-800 hover:text-white group' }}" href="{{ route('admin.transactions.index') }}">
                <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                Riwayat Transaksi
            </a>
            
            <a class="flex items-center px-4 py-2.5 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('admin.cash.*') ? 'bg-gray-800 text-white border-l-4 border-primary-500' : 'text-gray-400 hover:bg-gray-800 hover:text-white group' }}" href="{{ route('admin.cash.index') }}">
                <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Kas Masuk / Keluar
            </a>
            @endif

            <p class="px-4 pt-4 pb-2 text-xs font-semibold tracking-wider text-gray-500 uppercase">Laporan</p>
            <a class="flex items-center px-4 py-2.5 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('admin.reports.sales') ? 'bg-gray-800 text-white border-l-4 border-primary-500' : 'text-gray-400 hover:bg-gray-800 hover:text-white group' }}" href="{{ route('admin.reports.sales') }}">
                <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Penjualan Harian
            </a>
            <a class="flex items-center px-4 py-2.5 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('admin.reports.profit') ? 'bg-gray-800 text-white border-l-4 border-primary-500' : 'text-gray-400 hover:bg-gray-800 hover:text-white group' }}" href="{{ route('admin.reports.profit') }}">
                <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Laporan Keuntungan
            </a>

            @if(Auth::user()->role->name === \App\Enums\RoleEnum::ADMIN->value)
            <p class="px-4 pt-4 pb-2 text-xs font-semibold tracking-wider text-gray-500 uppercase">Sistem</p>
            <a class="flex items-center px-4 py-2.5 text-sm font-medium transition-colors rounded-lg {{ request()->routeIs('admin.users.*') ? 'bg-gray-800 text-white border-l-4 border-primary-500' : 'text-gray-400 hover:bg-gray-800 hover:text-white group' }}" href="{{ route('admin.users.index') }}">
                <svg class="w-5 h-5 mr-3 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Manajemen User
            </a>
            @endif

        </nav>
        
        <div class="mt-8 space-y-4">
            @if(in_array(Auth::user()->role->name, [\App\Enums\RoleEnum::ADMIN->value, \App\Enums\RoleEnum::CASHIER->value]))
            <a href="{{ route('pos.index') }}" class="flex items-center justify-center w-full px-4 py-3 text-sm font-bold text-white transition-colors bg-primary-600 rounded-lg hover:bg-primary-700 shadow-lg shadow-primary-500/30 active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                Buka POS
            </a>
            @endif

            <div class="flex items-center gap-x-4 p-4 mt-4 bg-gray-800 rounded-lg">
                <img class="object-cover w-10 h-10 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&color=7F9CF5&background=EBF4FF" alt="Avatar">
                
                <div class="flex flex-col">
                    <span class="text-sm font-medium text-white truncate max-w-[120px]">{{ Auth::user()->name }}</span>
                    <span class="text-xs text-gray-400 capitalize">{{ Auth::user()->role->display_name }}</span>
                </div>
            </div>
            
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="flex items-center w-full px-4 py-2 text-sm font-medium text-red-400 transition-colors rounded-lg hover:bg-gray-800 hover:text-red-300">
                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Logout
                </button>
            </form>
        </div>
    </div>
</aside>
