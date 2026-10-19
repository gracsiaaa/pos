<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Edit Produk') }}
            </h2>
        </div>
    </x-slot>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <div>
                    <label class="block text-sm font-medium text-gray-700">Nama Produk</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Kategori</label>
                    <select name="category_id" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('category_id', $product->category_id) == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @error('category_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">SKU</label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('sku') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Barcode</label>
                    <input type="text" name="barcode" value="{{ old('barcode', $product->barcode) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('barcode') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Harga Beli</label>
                    <input type="number" name="purchase_price" value="{{ old('purchase_price', $product->purchase_price) }}" required min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('purchase_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Harga Jual</label>
                    <input type="number" name="selling_price" value="{{ old('selling_price', $product->selling_price) }}" required min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('selling_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <!-- Cannot edit stock here directly, only min_stock -->
                <div>
                    <label class="block text-sm font-medium text-gray-700">Minimal Stok (Peringatan)</label>
                    <input type="number" name="min_stock" value="{{ old('min_stock', $product->min_stock) }}" required min="0" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('min_stock') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Supplier</label>
                    <select name="supplier_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">-- Pilih Supplier --</option>
                        @foreach($suppliers as $sup)
                            <option value="{{ $sup->id }}" {{ old('supplier_id', $product->supplier_id) == $sup->id ? 'selected' : '' }}>{{ $sup->name }}</option>
                        @endforeach
                    </select>
                    @error('supplier_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">Status Aktif</label>
                    <select name="is_active" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="1" {{ old('is_active', $product->is_active) == '1' ? 'selected' : '' }}>Aktif</option>
                        <option value="0" {{ old('is_active', $product->is_active) == '0' ? 'selected' : '' }}>Nonaktif</option>
                    </select>
                    @error('is_active') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700">Gambar Produk</label>
                                @if($product->image)
                                    <div class="mb-2">
                                        <img src="{{ str_starts_with($product->image, 'http') ? $product->image : Storage::url('products/' . $product->image) }}" alt="Preview" class="h-20 object-cover rounded">
                                    </div>
                                @endif
                                <input type="file" name="image" accept="image/*" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                <span class="text-xs text-gray-500">Biarkan kosong jika tidak ingin mengubah gambar.</span>
                                @error('image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                    <textarea name="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $product->description) }}</textarea>
                    @error('description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

            </div>

            <div class="mt-6 flex justify-end gap-2">
                <a href="{{ route('admin.products.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded shadow hover:bg-gray-300">Batal</a>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded shadow hover:bg-indigo-700">Perbarui Produk</button>
            </div>
        </form>
    </div>
</x-app-layout>