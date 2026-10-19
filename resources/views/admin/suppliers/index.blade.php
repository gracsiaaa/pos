<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Daftar Supplier') }}
            </h2>
            <a href="{{ route('admin.suppliers.create') }}" class="flex items-center bg-primary-600 hover:bg-primary-700 text-white font-medium py-2 px-4 rounded-lg shadow-sm transition-colors text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Tambah Supplier
            </a>
        </div>
    </x-slot>

    @if(session('success')) <x-toast type="success" :message="session('success')" /> @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden animate-fade-in">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Nama Supplier</th>
                        <th class="px-6 py-4 font-semibold">Kontak</th>
                        <th class="px-6 py-4 font-semibold">Telepon/Email</th>
                        <th class="px-6 py-4 font-semibold text-center">Jml Produk</th>
                        <th class="px-6 py-4 font-semibold text-center w-32">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($suppliers as $supplier)
                    <tr class="bg-white border-b border-gray-50 hover:bg-gray-50/50">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $supplier->name }}</td>
                        <td class="px-6 py-4">{{ $supplier->contact_person ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <div>{{ $supplier->phone ?? '-' }}</div>
                            <div class="text-xs text-gray-400">{{ $supplier->email }}</div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="bg-blue-50 text-blue-600 text-xs font-bold px-2.5 py-1 rounded-md">{{ $supplier->products_count }}</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.suppliers.edit', $supplier) }}" class="p-2 text-primary-600 hover:bg-primary-50 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>
                                <form action="{{ route('admin.suppliers.destroy', $supplier) }}" method="POST" class="inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-red-500 hover:bg-red-50 rounded-lg" onclick="return confirm('Hapus supplier?')"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-10 text-center text-gray-500">Belum ada supplier.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>