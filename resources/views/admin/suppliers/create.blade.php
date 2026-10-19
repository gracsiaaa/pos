<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ isset($supplier) ? __('Edit Supplier') : __('Tambah Supplier') }}
        </h2>
    </x-slot>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 animate-fade-in max-w-3xl">
        <form action="{{ isset($supplier) ? route('admin.suppliers.update', $supplier) : route('admin.suppliers.store') }}" method="POST">
            @csrf
            @if(isset($supplier)) @method('PUT') @endif
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Perusahaan / Supplier <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $supplier->name ?? '') }}" required class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kontak Person</label>
                    <input type="text" name="contact_person" value="{{ old('contact_person', $supplier->contact_person ?? '') }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $supplier->phone ?? '') }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $supplier->email ?? '') }}" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">
                </div>
                
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Lengkap</label>
                    <textarea name="address" rows="3" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500">{{ old('address', $supplier->address ?? '') }}</textarea>
                </div>
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <a href="{{ route('admin.suppliers.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200">Batal</a>
                <button type="submit" class="px-4 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 font-bold">Simpan</button>
            </div>
        </form>
    </div>
</x-app-layout>