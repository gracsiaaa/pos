<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.products.index') }}" class="p-2 text-gray-500 hover:text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Tambah Produk Baru') }}
            </h2>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            
            <!-- Section 1: Info Dasar -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Informasi Dasar</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Produk <span class="text-red-500">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="block w-full rounded-lg border-gray-300 bg-gray-50 text-gray-900 focus:bg-white focus:border-primary-500 focus:ring-primary-500 shadow-sm transition-colors">
                        @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori <span class="text-red-500">*</span></label>
                        <select name="category_id" required class="block w-full rounded-lg border-gray-300 bg-gray-50 text-gray-900 focus:bg-white focus:border-primary-500 focus:ring-primary-500 shadow-sm transition-colors">
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Supplier</label>
                        <select name="supplier_id" class="block w-full rounded-lg border-gray-300 bg-gray-50 text-gray-900 focus:bg-white focus:border-primary-500 focus:ring-primary-500 shadow-sm transition-colors">
                            <option value="">-- Tanpa Supplier --</option>
                            @foreach($suppliers as $sup)
                                <option value="{{ $sup->id }}" {{ old('supplier_id') == $sup->id ? 'selected' : '' }}>{{ $sup->name }}</option>
                            @endforeach
                        </select>
                        @error('supplier_id') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi Produk</label>
                        <textarea name="description" rows="3" class="block w-full rounded-lg border-gray-300 bg-gray-50 text-gray-900 focus:bg-white focus:border-primary-500 focus:ring-primary-500 shadow-sm transition-colors">{{ old('description') }}</textarea>
                        @error('description') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Section 2: Identitas & Gambar -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Identitas & Media</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">SKU <span class="text-red-500">*</span></label>
                        <input type="text" name="sku" value="{{ old('sku') }}" required class="block w-full rounded-lg border-gray-300 bg-gray-50 text-gray-900 focus:bg-white focus:border-primary-500 focus:ring-primary-500 shadow-sm uppercase">
                        <p class="text-xs text-gray-500 mt-1">Kode unik internal produk.</p>
                        @error('sku') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Barcode (Opsional)</label>
                        <input type="text" name="barcode" value="{{ old('barcode') }}" class="block w-full rounded-lg border-gray-300 bg-gray-50 text-gray-900 focus:bg-white focus:border-primary-500 focus:ring-primary-500 shadow-sm">
                        <p class="text-xs text-gray-500 mt-1">Kode scan barcode scanner.</p>
                        @error('barcode') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Produk</label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors cursor-pointer relative" id="image-drop-area">
                            <div class="space-y-1 text-center" id="image-preview-container">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600 justify-center">
                                    <label for="image-upload" class="relative cursor-pointer bg-white rounded-md font-medium text-primary-600 hover:text-primary-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-primary-500 px-1">
                                        <span>Upload file</span>
                                        <input id="image-upload" name="image" type="file" class="sr-only" accept="image/*" onchange="previewImage(event)">
                                    </label>
                                    <p class="pl-1">atau drag & drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG up to 2MB</p>
                            </div>
                            <img id="image-preview" src="#" alt="Preview" class="absolute inset-0 w-full h-full object-cover rounded-lg hidden opacity-0 transition-opacity duration-300">
                        </div>
                        @error('image') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Section 3: Harga & Stok -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="text-lg font-bold text-gray-800 mb-4 border-b border-gray-100 pb-2">Harga & Persediaan</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Beli Dasar <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" name="purchase_price" value="{{ old('purchase_price', 0) }}" required min="0" class="block w-full pl-10 rounded-lg border-gray-300 bg-gray-50 text-gray-900 focus:bg-white focus:border-primary-500 focus:ring-primary-500 shadow-sm">
                        </div>
                        @error('purchase_price') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Harga Jual (Retail) <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <span class="text-gray-500 sm:text-sm">Rp</span>
                            </div>
                            <input type="number" name="selling_price" value="{{ old('selling_price', 0) }}" required min="0" class="block w-full pl-10 rounded-lg border-gray-300 bg-gray-50 text-gray-900 focus:bg-white focus:border-primary-500 focus:ring-primary-500 shadow-sm font-bold text-primary-700">
                        </div>
                        @error('selling_price') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Stok Awal <span class="text-red-500">*</span></label>
                        <input type="number" name="stock" value="{{ old('stock', 0) }}" required min="0" class="block w-full rounded-lg border-gray-300 bg-gray-50 text-gray-900 focus:bg-white focus:border-primary-500 focus:ring-primary-500 shadow-sm">
                        @error('stock') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Peringatan Stok Minimum <span class="text-red-500">*</span></label>
                        <input type="number" name="min_stock" value="{{ old('min_stock', 5) }}" required min="0" class="block w-full rounded-lg border-gray-300 bg-gray-50 text-gray-900 focus:bg-white focus:border-primary-500 focus:ring-primary-500 shadow-sm">
                        @error('min_stock') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Status Publikasi</label>
                        <div class="flex gap-4 mt-2">
                            <label class="inline-flex items-center">
                                <input type="radio" name="is_active" value="1" class="text-primary-600 focus:ring-primary-500" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">Tersedia (Aktif)</span>
                            </label>
                            <label class="inline-flex items-center">
                                <input type="radio" name="is_active" value="0" class="text-red-600 focus:ring-red-500" {{ old('is_active') == '0' ? 'checked' : '' }}>
                                <span class="ml-2 text-gray-700">Sembunyikan (Nonaktif)</span>
                            </label>
                        </div>
                        @error('is_active') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-end gap-4 pt-4 pb-12">
                <a href="{{ route('admin.products.index') }}" class="px-6 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2.5 text-sm font-bold text-white bg-primary-600 border border-transparent rounded-lg shadow-sm hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 transition-colors flex items-center">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Simpan Produk
                </button>
            </div>
        </form>
    </div>

    <script>
        function previewImage(event) {
            const input = event.target;
            const previewContainer = document.getElementById('image-preview-container');
            const previewImage = document.getElementById('image-preview');

            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    previewImage.src = e.target.result;
                    previewImage.classList.remove('hidden');
                    // Slight delay to allow display:block to apply before transition
                    setTimeout(() => previewImage.classList.remove('opacity-0'), 50);
                    previewContainer.classList.add('opacity-0');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</x-app-layout>