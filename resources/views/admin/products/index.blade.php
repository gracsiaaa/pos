<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Data Produk') }}
            </h2>
            <a href="{{ route('admin.products.create') }}" class="flex items-center bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah Produk
            </a>
        </div>
    </x-slot>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        
        <!-- Toolbar / Filter -->
        <div class="p-4 border-b border-gray-100 flex flex-col md:flex-row gap-4 items-center justify-between bg-gray-50/50">
            <div class="relative w-full md:w-96">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input type="text" class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full pl-10 p-2.5 shadow-sm" placeholder="Cari nama, SKU, atau barcode...">
            </div>
            
            <div class="flex gap-2 w-full md:w-auto">
                <select class="bg-white border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 shadow-sm">
                    <option value="">Semua Kategori</option>
                    <!-- Kategori option map here -->
                </select>
                <button class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 shadow-sm whitespace-nowrap">
                    Filter
                </button>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 m-4" role="alert">
                <p class="font-medium">{{ session('success') }}</p>
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th scope="col" class="px-6 py-4 font-semibold">Produk</th>
                        <th scope="col" class="px-6 py-4 font-semibold">SKU / Barcode</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Kategori</th>
                        <th scope="col" class="px-6 py-4 font-semibold text-right">Harga Jual</th>
                        <th scope="col" class="px-6 py-4 font-semibold text-center">Stok</th>
                        <th scope="col" class="px-6 py-4 font-semibold text-center">Status</th>
                        <th scope="col" class="px-6 py-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr class="bg-white border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($product->image)
                                    <img src="{{ str_starts_with($product->image, 'http') ? $product->image : Storage::url('products/' . $product->image) }}" class="w-10 h-10 rounded-lg object-cover border border-gray-200">
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center border border-gray-200 text-gray-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                                <div class="font-medium text-gray-900 line-clamp-2 max-w-xs" title="{{ $product->name }}">{{ $product->name }}</div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-gray-900 font-medium">{{ $product->sku }}</div>
                            <div class="text-xs text-gray-400">{{ $product->barcode ?: '-' }}</div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="bg-gray-100 text-gray-600 text-xs font-medium px-2.5 py-1 rounded-md">{{ $product->category->name ?? '-' }}</span>
                        </td>
                        <td class="px-6 py-4 text-right font-medium text-gray-900">
                            Rp {{ number_format($product->selling_price, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($product->stock <= $product->min_stock)
                                <span class="bg-red-50 text-red-600 text-xs font-bold px-2.5 py-1 rounded-md ring-1 ring-inset ring-red-500/20">{{ $product->stock }}</span>
                            @elseif($product->stock <= ($product->min_stock * 1.5))
                                <span class="bg-amber-50 text-amber-600 text-xs font-bold px-2.5 py-1 rounded-md ring-1 ring-inset ring-amber-500/20">{{ $product->stock }}</span>
                            @else
                                <span class="bg-emerald-50 text-emerald-600 text-xs font-bold px-2.5 py-1 rounded-md ring-1 ring-inset ring-emerald-500/20">{{ $product->stock }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if($product->is_active)
                                <div class="flex items-center justify-center">
                                    <div class="h-2.5 w-2.5 rounded-full bg-emerald-500 mr-2"></div> Aktif
                                </div>
                            @else
                                <div class="flex items-center justify-center">
                                    <div class="h-2.5 w-2.5 rounded-full bg-red-500 mr-2"></div> Nonaktif
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.products.edit', $product) }}" class="p-2 text-primary-600 hover:bg-primary-50 rounded-lg transition-colors tooltip" title="Edit Produk">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors tooltip" title="Hapus / Nonaktifkan" onclick="return confirm('Yakin ingin menghapus / menonaktifkan produk ini?')">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-3 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                            <p class="text-base font-medium">Belum ada data produk</p>
                            <p class="text-sm mt-1">Silakan tambahkan produk baru untuk mulai menggunakan sistem POS.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($products->hasPages())
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $products->links() }}
        </div>
        @endif
    </div>
</x-app-layout>