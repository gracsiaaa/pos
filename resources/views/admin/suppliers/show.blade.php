<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <a href="{{ route('admin.suppliers.index') }}" class="text-xs text-primary-600 hover:underline mb-1 inline-flex items-center gap-1">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                    Kembali ke Daftar Supplier
                </a>
                <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                    {{ $supplier->name }}
                </h2>
            </div>
            <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="flex items-center bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition-colors text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                Edit Supplier
            </a>
        </div>
    </x-slot>

    <div class="space-y-6 animate-fade-in">
        <!-- Info Card -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Informasi Supplier</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <span class="text-xs text-gray-500 block">Kontak Person</span>
                    <span class="font-semibold text-gray-800">{{ $supplier->contact_person ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-xs text-gray-500 block">No Telepon</span>
                    <span class="font-semibold text-gray-800">{{ $supplier->phone ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-xs text-gray-500 block">Email</span>
                    <span class="font-semibold text-gray-800">{{ $supplier->email ?? '-' }}</span>
                </div>
                <div>
                    <span class="text-xs text-gray-500 block">Total Produk Disuplai</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-100 text-blue-800 mt-0.5">
                        {{ $supplier->products->count() }} Produk
                    </span>
                </div>
                <div class="md:col-span-4">
                    <span class="text-xs text-gray-500 block">Alamat</span>
                    <span class="text-gray-700">{{ $supplier->address ?? '-' }}</span>
                </div>
            </div>
        </div>

        <!-- Products List -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                <div>
                    <h3 class="font-bold text-lg text-gray-800">Daftar Produk yang Disuplai</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Semua produk dan stok aktif yang dipasok oleh supplier ini</p>
                </div>
                <a href="{{ route('admin.products.create') }}" class="text-xs text-primary-600 hover:underline font-semibold">
                    + Tambah Produk Baru
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-gray-500">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                        <tr>
                            <th class="px-6 py-4 font-semibold">Produk</th>
                            <th class="px-6 py-4 font-semibold">SKU / Barcode</th>
                            <th class="px-6 py-4 font-semibold">Kategori</th>
                            <th class="px-6 py-4 font-semibold text-right">Harga Beli</th>
                            <th class="px-6 py-4 font-semibold text-right">Harga Jual</th>
                            <th class="px-6 py-4 font-semibold text-center">Stok</th>
                            <th class="px-6 py-4 font-semibold text-center">Status</th>
                            <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse($supplier->products as $product)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($product->image)
                                        <img src="{{ str_starts_with($product->image, 'http') ? $product->image : Storage::url('products/' . $product->image) }}" class="w-10 h-10 rounded-lg object-cover border border-gray-200">
                                    @else
                                        <div class="w-10 h-10 rounded-lg bg-gray-100 flex items-center justify-center border border-gray-200 text-gray-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        </div>
                                    @endif
                                    <div class="font-medium text-gray-900">{{ $product->name }}</div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-mono text-xs text-gray-700">{{ $product->sku }}</div>
                                <div class="font-mono text-[11px] text-gray-400">{{ $product->barcode ?? '-' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="bg-gray-100 text-gray-700 text-xs px-2.5 py-1 rounded-md">{{ $product->category->name ?? '-' }}</span>
                            </td>
                            <td class="px-6 py-4 text-right font-medium">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-right font-semibold text-gray-900">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                @if($product->stock <= $product->min_stock)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-amber-100 text-amber-800">
                                        {{ $product->stock }} (Min: {{ $product->min_stock }})
                                    </span>
                                @else
                                    <span class="font-semibold text-gray-800">{{ $product->stock }}</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($product->is_active)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-emerald-100 text-emerald-800">Aktif</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-semibold bg-gray-100 text-gray-600">Non-Aktif</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('admin.products.edit', $product) }}" class="p-2 text-primary-600 hover:bg-primary-50 rounded-lg inline-block" title="Edit Produk">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="px-6 py-10 text-center text-gray-400">
                                Supplier ini belum memiliki produk terdaftar.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
