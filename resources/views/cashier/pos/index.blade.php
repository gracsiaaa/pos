<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Kasir</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 h-screen flex flex-col font-sans overflow-hidden text-gray-800" x-data="posApp()" @keydown.window="handleGlobalKeydown($event)">

    <!-- Header -->
    <header class="bg-white shadow-sm border-b border-gray-200 flex justify-between items-center px-6 py-3 shrink-0 z-20">
        <div class="flex items-center gap-3">
            <div class="p-2 bg-primary-600 rounded-lg">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
            <div>
                <h1 class="text-xl font-bold text-gray-900 leading-tight">Terminal Kasir</h1>
                <p class="text-xs font-medium text-gray-500" x-text="currentTime"></p>
            </div>
        </div>
        
        <div class="flex items-center gap-4 sm:gap-6">
            <div class="hidden md:flex items-center gap-2">
                <span class="flex h-3 w-3">
                  <span class="animate-ping absolute inline-flex h-3 w-3 rounded-full bg-emerald-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                </span>
                <span class="text-sm font-medium text-gray-600">Sistem Online</span>
            </div>
            
            <div class="h-8 w-px bg-gray-200 hidden md:block"></div>

            <!-- Dashboard Button (Styled pill badge) -->
            @if(in_array(Auth::user()->role->name, [\App\Enums\RoleEnum::ADMIN->value, \App\Enums\RoleEnum::OWNER->value]))
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 px-3.5 py-2 rounded-xl bg-gray-900 text-white hover:bg-gray-800 text-xs font-bold shadow-sm transition-all hover:scale-105 active:scale-95">
                <svg class="w-4 h-4 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"></path></svg>
                <span>Dashboard Admin</span>
            </a>
            @endif

            <div class="flex items-center gap-3">
                <div class="flex flex-col items-end">
                    <span class="text-sm font-bold text-gray-800">{{ Auth::user()->name }}</span>
                    <span class="text-[10px] text-gray-400 uppercase tracking-wider font-semibold">{{ Auth::user()->role->display_name }}</span>
                </div>
                <form method="POST" action="{{ route('logout') }}" class="ml-2">
                    @csrf
                    <button type="submit" class="p-2 text-red-500 bg-red-50 hover:bg-red-100 rounded-lg transition-colors tooltip" title="Logout">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    </button>
                </form>
            </div>
        </div>
    </header>

    <div class="flex-1 flex overflow-hidden">
        <!-- Main Content: Product Selection -->
        <main class="flex-1 flex flex-col p-4 sm:p-6 bg-gray-50 relative">
            <!-- Search / Barcode -->
            <div class="mb-6 relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" x-ref="searchInput" x-model="search" placeholder="Cari nama produk, SKU, atau scan barcode... (Tekan Enter)" 
                       class="w-full pl-12 pr-4 py-4 rounded-2xl border-0 shadow-sm ring-1 ring-inset ring-gray-200 focus:ring-2 focus:ring-inset focus:ring-primary-600 text-lg transition-all" 
                       @keydown.enter.prevent="handleBarcodeScan()">
                <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                    <kbd class="hidden sm:inline-flex items-center gap-1 px-2 font-sans text-xs text-gray-400 bg-gray-100 border border-gray-200 rounded">
                        <span class="text-lg leading-none">↵</span> Enter
                    </kbd>
                </div>
            </div>
            
            <!-- Products Grid -->
            <div class="flex-1 overflow-y-auto pr-2 pb-2 -mr-2">
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                    <template x-for="product in filteredProducts" :key="product.id">
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden cursor-pointer hover:shadow-md hover:border-primary-300 transition-all flex flex-col h-full relative" 
                             :class="product.stock <= 0 ? 'opacity-60 grayscale-[50%]' : ''"
                             @click="product.stock > 0 ? addToCart(product) : null">
                            
                            <!-- Stock Badge -->
                            <div class="absolute top-2 right-2 z-10">
                                <span x-show="product.stock > 0 && product.stock > product.min_stock" class="px-2 py-1 text-[10px] font-bold bg-emerald-100 text-emerald-700 rounded-full shadow-sm">Stok: <span x-text="product.stock"></span></span>
                                <span x-show="product.stock > 0 && product.stock <= product.min_stock" class="px-2 py-1 text-[10px] font-bold bg-amber-100 text-amber-700 rounded-full shadow-sm">Sisa: <span x-text="product.stock"></span></span>
                                <span x-show="product.stock <= 0" class="px-2 py-1 text-[10px] font-bold bg-red-100 text-red-700 rounded-full shadow-sm">HABIS</span>
                            </div>

                            <!-- Product Image -->
                            <div class="h-32 bg-gray-50 flex items-center justify-center p-2 border-b border-gray-50">
                                <template x-if="product.image_url">
                                    <img :src="product.image_url" :alt="product.name" class="max-h-full max-w-full object-contain mix-blend-multiply">
                                </template>
                                <template x-if="!product.image_url">
                                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </template>
                            </div>
                            
                            <!-- Product Info -->
                            <div class="p-4 flex-1 flex flex-col justify-between">
                                <div>
                                    <div class="text-[10px] font-bold text-gray-400 mb-1 uppercase tracking-wider" x-text="product.sku"></div>
                                    <h3 class="font-bold text-gray-800 text-sm leading-tight mb-2 line-clamp-2" x-text="product.name"></h3>
                                </div>
                                <div class="text-primary-600 font-bold text-lg" x-text="formatMoney(product.selling_price)"></div>
                            </div>
                        </div>
                    </template>
                </div>
                
                <!-- Empty State -->
                <div x-show="filteredProducts.length === 0" class="h-full flex flex-col items-center justify-center text-gray-400">
                    <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    <p class="text-lg font-medium">Produk tidak ditemukan</p>
                </div>
            </div>
        </main>

        <!-- Sidebar: Cart & Checkout -->
        <aside class="w-[400px] flex flex-col shadow-2xl z-10 shrink-0 bg-white border-l border-gray-200">
            <!-- Cart Header -->
            <div class="p-5 bg-gradient-to-r from-primary-600 to-primary-800 text-white shadow-inner">
                <div class="flex justify-between items-center">
                    <h2 class="font-bold text-lg flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                        Keranjang
                    </h2>
                    <span class="bg-white/20 text-white px-3 py-1 rounded-full text-xs font-bold" x-text="cart.length + ' Item'"></span>
                </div>
            </div>
            
            <!-- Cart Items -->
            <div class="flex-1 overflow-y-auto p-3 bg-gray-50/50">
                <template x-if="cart.length === 0">
                    <div class="h-full flex flex-col items-center justify-center text-gray-400 p-8 text-center">
                        <svg class="w-16 h-16 mb-4 opacity-30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        <p>Keranjang kosong.<br>Silakan pilih produk atau scan barcode.</p>
                    </div>
                </template>
                
                <div class="space-y-2">
                    <template x-for="(item, index) in cart" :key="item.product_id">
                        <div class="bg-white border border-gray-200 rounded-xl p-3 shadow-sm relative group hover:border-primary-300 transition-colors">
                            <button @click="removeFromCart(index)" class="absolute top-2 right-2 text-gray-400 hover:text-red-500 bg-gray-50 hover:bg-red-50 rounded-md p-1 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                            
                            <div class="font-bold text-gray-800 text-sm pr-8 leading-tight mb-1" x-text="item.name"></div>
                            <div class="text-xs text-gray-500 mb-3" x-text="formatMoney(item.price)"></div>
                            
                            <div class="flex justify-between items-end">
                                <div class="flex items-center bg-gray-100 rounded-lg p-0.5 border border-gray-200">
                                    <button @click="updateQuantity(index, -1)" class="w-7 h-7 flex items-center justify-center bg-white rounded-md text-gray-600 hover:bg-gray-50 shadow-sm border border-gray-200 focus:outline-none">-</button>
                                    <input type="number" x-model.number="item.quantity" class="w-10 text-center bg-transparent border-0 p-0 text-sm font-bold focus:ring-0" min="1" @change="validateQuantity(index)">
                                    <button @click="updateQuantity(index, 1)" class="w-7 h-7 flex items-center justify-center bg-white rounded-md text-gray-600 hover:bg-gray-50 shadow-sm border border-gray-200 focus:outline-none">+</button>
                                </div>
                                <div class="font-bold text-gray-900" x-text="formatMoney(item.price * item.quantity)"></div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

            <!-- Totals & Payment -->
            <div class="p-5 bg-white border-t border-gray-200 shadow-[0_-4px_6px_-1px_rgba(0,0,0,0.05)]">
                <div class="space-y-2 mb-4">
                    <div class="flex justify-between text-sm text-gray-500">
                        <span>Subtotal</span>
                        <span class="font-medium text-gray-700" x-text="formatMoney(subtotal)"></span>
                    </div>
                    <!-- Placeholder for tax/discount if needed -->
                    <div class="flex justify-between items-end pt-2 border-t border-dashed border-gray-200">
                        <span class="text-base font-bold text-gray-800">Total Bayar</span>
                        <span class="text-2xl font-black text-primary-600" x-text="formatMoney(total)"></span>
                    </div>
                </div>
                
                <div class="mb-4">
                    <div class="grid grid-cols-3 gap-2 mb-3">
                        <label class="cursor-pointer">
                            <input type="radio" x-model="paymentMethod" value="cash" class="peer sr-only">
                            <div class="text-center px-2 py-2 text-sm font-medium rounded-lg border border-gray-200 peer-checked:border-primary-500 peer-checked:bg-primary-50 peer-checked:text-primary-700 transition-colors">Tunai</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" x-model="paymentMethod" value="debit" class="peer sr-only">
                            <div class="text-center px-2 py-2 text-sm font-medium rounded-lg border border-gray-200 peer-checked:border-primary-500 peer-checked:bg-primary-50 peer-checked:text-primary-700 transition-colors">Debit</div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" x-model="paymentMethod" value="qris" class="peer sr-only">
                            <div class="text-center px-2 py-2 text-sm font-medium rounded-lg border border-gray-200 peer-checked:border-primary-500 peer-checked:bg-primary-50 peer-checked:text-primary-700 transition-colors">QRIS</div>
                        </label>
                    </div>
                </div>
                
                <div class="mb-5" x-show="paymentMethod === 'cash'" x-collapse>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="font-bold text-gray-500">Rp</span>
                        </div>
                        <input type="number" x-model.number="paidAmount" class="w-full pl-12 pr-4 py-3 bg-gray-50 border border-gray-300 rounded-xl text-xl font-bold text-right focus:bg-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500" placeholder="0">
                    </div>
                    
                    <!-- Quick Cash Buttons -->
                    <div class="grid grid-cols-4 gap-2 mt-2">
                        <button type="button" @click="setQuickCash(20000)" class="py-1.5 text-xs font-semibold bg-gray-100 hover:bg-gray-200 text-gray-700 rounded border border-gray-200">20K</button>
                        <button type="button" @click="setQuickCash(50000)" class="py-1.5 text-xs font-semibold bg-gray-100 hover:bg-gray-200 text-gray-700 rounded border border-gray-200">50K</button>
                        <button type="button" @click="setQuickCash(100000)" class="py-1.5 text-xs font-semibold bg-gray-100 hover:bg-gray-200 text-gray-700 rounded border border-gray-200">100K</button>
                        <button type="button" @click="setQuickCash('exact')" class="py-1.5 text-xs font-semibold bg-primary-100 hover:bg-primary-200 text-primary-700 rounded border border-primary-200">PAS</button>
                    </div>

                    <div class="flex justify-between items-center mt-3 p-3 rounded-lg" :class="changeAmount >= 0 ? 'bg-emerald-50 border border-emerald-100' : 'bg-red-50 border border-red-100'">
                        <span class="font-semibold text-sm" :class="changeAmount >= 0 ? 'text-emerald-700' : 'text-red-700'">Kembalian</span>
                        <span class="font-bold text-lg" :class="changeAmount >= 0 ? 'text-emerald-700' : 'text-red-700'" x-text="formatMoney(changeAmount)"></span>
                    </div>
                </div>

                <button @click="processCheckout()" 
                        class="w-full flex justify-center items-center py-4 px-4 border border-transparent rounded-xl shadow-lg text-lg font-bold text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 disabled:opacity-50 disabled:cursor-not-allowed transition-all relative overflow-hidden"
                        :disabled="cart.length === 0 || (paymentMethod === 'cash' && changeAmount < 0) || isProcessing">
                    <span x-show="!isProcessing" class="flex items-center">
                        <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        Proses Pembayaran (F2)
                    </span>
                    <span x-show="isProcessing" class="flex items-center">
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                        Memproses...
                    </span>
                </button>
            </div>
        </aside>
    </div>

    <!-- Receipt Modal -->
    <div x-show="showReceipt" class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4" x-transition.opacity style="display: none;">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-md overflow-hidden" @click.outside="closeReceipt()">
            <!-- Receipt Header -->
            <div class="bg-emerald-500 p-6 text-center text-white relative">
                <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-inner">
                    <svg class="w-8 h-8 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <h2 class="text-2xl font-bold">Transaksi Berhasil!</h2>
                <p class="opacity-90 mt-1" x-text="lastInvoice"></p>
                
                <!-- Receipt tear edge effect -->
                <div class="absolute -bottom-2 left-0 right-0 flex justify-between px-2">
                    <template x-for="i in 20">
                        <div class="w-3 h-3 bg-white rounded-full"></div>
                    </template>
                </div>
            </div>
            
            <div class="p-8 pt-6">
                <!-- Receipt Details (Simulation) -->
                <div class="border border-dashed border-gray-300 rounded-lg p-4 bg-gray-50 mb-6 font-mono text-sm">
                    <div class="text-center font-bold mb-2">POS RETAIL STORE</div>
                    <div class="text-center text-gray-500 mb-4 pb-2 border-b border-dashed border-gray-300" x-text="currentTime"></div>
                    
                    <div class="flex justify-between mb-1">
                        <span>Total Bayar</span>
                        <span x-text="formatMoney(lastTotal)"></span>
                    </div>
                    <div class="flex justify-between mb-1">
                        <span x-text="lastPaymentMethod === 'cash' ? 'Tunai' : (lastPaymentMethod === 'qris' ? 'QRIS' : 'Debit')"></span>
                        <span x-text="formatMoney(lastPaid)"></span>
                    </div>
                    <div class="flex justify-between font-bold mt-2 pt-2 border-t border-dashed border-gray-300">
                        <span>Kembalian</span>
                        <span x-text="formatMoney(lastChange)"></span>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-3">
                    <button @click="closeReceipt()" class="py-3 px-4 bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold rounded-xl transition-colors focus:ring-2 focus:ring-gray-300">
                        Transaksi Baru (Esc)
                    </button>
                    <button @click="printReceipt()" class="py-3 px-4 bg-primary-600 hover:bg-primary-700 text-white font-bold rounded-xl shadow-md transition-colors focus:ring-2 focus:ring-primary-500 flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Cetak Struk
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Inject data products directly and add image_url helper -->
    @php
        $productsWithImages = $products->map(function($p) {
            $p->image_url = $p->image ? (str_starts_with($p->image, 'http') ? $p->image : Storage::url('products/' . $p->image)) : null;
            return $p;
        });
    @endphp

    <script>
        function posApp() {
            return {
                products: @json($productsWithImages),
                search: '',
                cart: [],
                paymentMethod: 'cash',
                paidAmount: null,
                isProcessing: false,
                showReceipt: false,
                lastInvoice: '',
                lastChange: 0,
                lastTotal: 0,
                lastPaid: 0,
                lastPaymentMethod: '',
                currentTime: '',
                
                init() {
                    this.updateTime();
                    setInterval(() => this.updateTime(), 1000);
                    // Focus search input on load
                    setTimeout(() => this.$refs.searchInput.focus(), 100);
                },

                updateTime() {
                    const now = new Date();
                    const options = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
                    this.currentTime = now.toLocaleDateString('id-ID', options);
                },

                handleGlobalKeydown(e) {
                    // Prevent default for F2 and Esc to handle shortcuts
                    if (e.key === 'F2') {
                        e.preventDefault();
                        if (this.cart.length > 0 && !this.showReceipt && !this.isProcessing) {
                            if (this.paymentMethod === 'cash' && this.changeAmount < 0) return;
                            this.processCheckout();
                        }
                    } else if (e.key === 'Escape' && this.showReceipt) {
                        this.closeReceipt();
                    }
                },
                
                get filteredProducts() {
                    if (this.search === '') return this.products;
                    const s = this.search.toLowerCase();
                    return this.products.filter(p => 
                        p.name.toLowerCase().includes(s) || 
                        (p.barcode && p.barcode.toLowerCase().includes(s)) ||
                        (p.sku && p.sku.toLowerCase().includes(s))
                    );
                },

                get subtotal() {
                    return this.cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                },

                get total() {
                    return this.subtotal; 
                },

                get changeAmount() {
                    if (this.paymentMethod !== 'cash') return 0;
                    if (!this.paidAmount) return -this.total;
                    return this.paidAmount - this.total;
                },

                setQuickCash(amount) {
                    if (amount === 'exact') {
                        this.paidAmount = this.total;
                    } else {
                        // Jika nominal yang ditekan lebih kecil dari total, abaikan atau tambah?
                        // Di sini kita timpa valuenya
                        this.paidAmount = amount;
                    }
                },

                handleBarcodeScan() {
                    if (this.search === '') return;
                    const s = this.search.toLowerCase();
                    const exactMatch = this.products.find(p => 
                        (p.barcode && p.barcode.toLowerCase() === s) || 
                        (p.sku && p.sku.toLowerCase() === s)
                    );
                    
                    if (exactMatch && exactMatch.stock > 0) {
                        this.addToCart(exactMatch);
                        this.search = ''; 
                    } else if (this.filteredProducts.length === 1 && this.filteredProducts[0].stock > 0) {
                        // Jika hasil search cuma 1 dan user tekan enter, masukkan ke cart
                        this.addToCart(this.filteredProducts[0]);
                        this.search = '';
                    }
                },

                addToCart(product) {
                    const existingIndex = this.cart.findIndex(i => i.product_id === product.id);
                    if (existingIndex > -1) {
                        if (this.cart[existingIndex].quantity < product.stock) {
                            this.cart[existingIndex].quantity++;
                        } else {
                            // Flash effect or small notification here instead of alert ideally
                            alert('Stok tidak mencukupi!');
                        }
                    } else {
                        this.cart.unshift({ // Add to top of list
                            product_id: product.id,
                            name: product.name,
                            price: product.selling_price,
                            quantity: 1,
                            max_stock: product.stock
                        });
                    }
                },

                removeFromCart(index) {
                    this.cart.splice(index, 1);
                },

                updateQuantity(index, change) {
                    const item = this.cart[index];
                    const newQ = item.quantity + change;
                    if (newQ > 0 && newQ <= item.max_stock) {
                        item.quantity = newQ;
                    }
                },

                validateQuantity(index) {
                    const item = this.cart[index];
                    if (item.quantity > item.max_stock) item.quantity = item.max_stock;
                    if (item.quantity < 1) item.quantity = 1;
                },

                closeReceipt() {
                    this.showReceipt = false;
                    this.cart = [];
                    this.paidAmount = null;
                    this.search = '';
                    this.paymentMethod = 'cash';
                    
                    // Reload to get fresh stock
                    setTimeout(() => {
                        window.location.reload(); 
                    }, 300);
                },
                
                printReceipt() {
                    let receiptContent = `
                        <div style="font-family: monospace; width: 300px; margin: 0 auto;">
                            <div style="text-align: center; font-weight: bold; margin-bottom: 10px;">
                                POS RETAIL STORE<br>
                                <span style="font-size: 12px; font-weight: normal;">Jl. Merdeka No.123, Kota</span>
                            </div>
                            <div style="border-bottom: 1px dashed #000; margin-bottom: 10px;"></div>
                            <div style="font-size: 12px; margin-bottom: 10px;">
                                Invoice: ${this.lastInvoice}<br>
                                Kasir: {{ Auth::user()->name }}<br>
                                Waktu: ${this.currentTime}
                            </div>
                            <div style="border-bottom: 1px dashed #000; margin-bottom: 10px;"></div>
                    `;

                    this.cart.forEach(item => {
                        receiptContent += `
                            <div style="font-size: 12px; margin-bottom: 5px;">
                                <div>${item.name}</div>
                                <div style="display: flex; justify-content: space-between;">
                                    <span>${item.quantity} x ${this.formatMoney(item.price)}</span>
                                    <span>${this.formatMoney(item.price * item.quantity)}</span>
                                </div>
                            </div>
                        `;
                    });

                    receiptContent += `
                            <div style="border-bottom: 1px dashed #000; margin-bottom: 10px; margin-top: 10px;"></div>
                            <div style="font-size: 12px; display: flex; justify-content: space-between; font-weight: bold;">
                                <span>TOTAL</span>
                                <span>${this.formatMoney(this.lastTotal)}</span>
                            </div>
                            <div style="font-size: 12px; display: flex; justify-content: space-between; margin-top: 5px;">
                                <span>Bayar (${this.lastPaymentMethod.toUpperCase()})</span>
                                <span>${this.formatMoney(this.lastPaid)}</span>
                            </div>
                            <div style="font-size: 12px; display: flex; justify-content: space-between; margin-top: 5px;">
                                <span>Kembali</span>
                                <span>${this.formatMoney(this.lastChange)}</span>
                            </div>
                            <div style="border-bottom: 1px dashed #000; margin-bottom: 10px; margin-top: 10px;"></div>
                            <div style="text-align: center; font-size: 12px; margin-top: 10px;">
                                Terima Kasih Atas Kunjungan Anda<br>
                                Barang yang dibeli tidak dapat ditukar
                            </div>
                        </div>
                    `;

                    // Buka window baru untuk print
                    const printWindow = window.open('', '', 'width=400,height=600');
                    printWindow.document.write('<html><head><title>Print Struk</title></head><body style="margin:0; padding:10px;">');
                    printWindow.document.write(receiptContent);
                    printWindow.document.write('</body></html>');
                    
                    printWindow.document.close();
                    printWindow.focus();
                    
                    setTimeout(() => {
                        printWindow.print();
                        printWindow.close();
                        this.closeReceipt();
                    }, 250);
                },

                formatMoney(amount) {
                    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(amount);
                },

                async processCheckout() {
                    this.isProcessing = true;
                    try {
                        const payload = {
                            items: this.cart.map(i => ({
                                product_id: i.product_id,
                                quantity: i.quantity,
                                discount: 0
                            })),
                            paid_amount: this.paymentMethod === 'cash' ? this.paidAmount : this.total,
                            payment_method: this.paymentMethod,
                        };

                        const response = await fetch('{{ route('pos.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify(payload)
                        });

                        const data = await response.json();
                        
                        if (response.ok && data.success) {
                            this.lastInvoice = data.invoice_number;
                            this.lastChange = data.change_amount;
                            this.lastTotal = this.total;
                            this.lastPaid = payload.paid_amount;
                            this.lastPaymentMethod = this.paymentMethod;
                            
                            this.showReceipt = true;
                        } else {
                            alert(data.message || 'Terjadi kesalahan saat checkout.');
                        }
                    } catch (error) {
                        alert('Gagal terhubung ke server. Periksa koneksi jaringan.');
                        console.error(error);
                    } finally {
                        this.isProcessing = false;
                    }
                }
            }
        }
    </script>
</body>
</html>
