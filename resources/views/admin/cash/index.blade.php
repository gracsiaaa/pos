<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Arus Kas Harian') }}
        </h2>
    </x-slot>

    @if(session('success')) <x-toast type="success" :message="session('success')" /> @endif
    @if(session('error')) <x-toast type="error" :message="session('error')" /> @endif

    <!-- Saldo Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-emerald-500 rounded-2xl shadow-sm text-white p-6 relative overflow-hidden">
            <svg class="absolute -right-4 -bottom-4 w-32 h-32 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            <div class="text-emerald-100 font-medium text-sm mb-1">Total Kas Masuk</div>
            <div class="text-3xl font-bold">Rp {{ number_format($cashIn, 0, ',', '.') }}</div>
        </div>
        
        <div class="bg-red-500 rounded-2xl shadow-sm text-white p-6 relative overflow-hidden">
            <svg class="absolute -right-4 -bottom-4 w-32 h-32 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
            <div class="text-red-100 font-medium text-sm mb-1">Total Kas Keluar</div>
            <div class="text-3xl font-bold">Rp {{ number_format($cashOut, 0, ',', '.') }}</div>
        </div>
        
        <div class="bg-gray-900 rounded-2xl shadow-sm text-white p-6 relative overflow-hidden">
            <svg class="absolute -right-4 -bottom-4 w-32 h-32 opacity-20" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            <div class="text-gray-400 font-medium text-sm mb-1">Saldo Kas Bersih</div>
            <div class="text-3xl font-bold">Rp {{ number_format($balance, 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden animate-fade-in" x-data="{ 
        showModal: false, 
        type: 'in'
    }">
        <div class="p-4 border-b border-gray-100 bg-gray-50/50 flex flex-col md:flex-row gap-4 items-center justify-between">
            <form method="GET" class="flex gap-2">
                <input type="date" name="date" value="{{ $date }}" class="rounded-lg border-gray-300 text-sm focus:ring-primary-500 focus:border-primary-500">
                <button type="submit" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50">Filter</button>
            </form>
            
            <div class="flex gap-2">
                <button @click="showModal = true; type = 'in'" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-bold hover:bg-emerald-700 flex items-center shadow-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg> Kas Masuk
                </button>
                <button @click="showModal = true; type = 'out'" class="px-4 py-2 bg-red-600 text-white rounded-lg text-sm font-bold hover:bg-red-700 flex items-center shadow-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg> Kas Keluar
                </button>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Tipe</th>
                        <th class="px-6 py-4 font-semibold">Keterangan</th>
                        <th class="px-6 py-4 font-semibold">Referensi</th>
                        <th class="px-6 py-4 font-semibold">Oleh</th>
                        <th class="px-6 py-4 font-semibold text-right">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $trx)
                    <tr class="bg-white border-b border-gray-50 hover:bg-gray-50/50">
                        <td class="px-6 py-4">
                            @if($trx->type->value === 'in')
                                <span class="bg-emerald-50 text-emerald-600 px-2.5 py-1 rounded-md text-xs font-bold border border-emerald-100 flex items-center w-fit"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg> MASUK</span>
                            @else
                                <span class="bg-red-50 text-red-600 px-2.5 py-1 rounded-md text-xs font-bold border border-red-100 flex items-center w-fit"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg> KELUAR</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-900">{{ $trx->description }}</td>
                        <td class="px-6 py-4 text-xs text-gray-400 font-mono">{{ $trx->reference_type ?? '-' }}</td>
                        <td class="px-6 py-4 text-xs font-medium">{{ $trx->user->name }}</td>
                        <td class="px-6 py-4 text-right font-bold text-gray-900">
                            {{ $trx->type->value === 'in' ? '+' : '-' }} Rp {{ number_format($trx->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-6 py-10 text-center text-gray-500">Tidak ada transaksi kas di tanggal ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Modal Tambah Kas -->
        <div x-show="showModal" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm" style="display: none;">
            <div @click.outside="showModal = false" class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden animate-slide-up">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center" :class="type === 'in' ? 'bg-emerald-50' : 'bg-red-50'">
                    <h3 class="font-bold" :class="type === 'in' ? 'text-emerald-800' : 'text-red-800'" x-text="type === 'in' ? 'Tambah Kas Masuk' : 'Tambah Kas Keluar'"></h3>
                    <button @click="showModal = false" class="text-gray-400 hover:text-gray-600">&times;</button>
                </div>
                
                <form action="{{ url('admin/cash') }}" method="POST" class="p-6 space-y-4">
                    @csrf
                    <input type="hidden" name="type" x-model="type">
                    <input type="hidden" name="date" value="{{ date('Y-m-d') }}">
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Nominal (Rp)</label>
                        <input type="number" name="amount" required min="1" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-lg font-bold">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan / Deskripsi</label>
                        <textarea name="description" required rows="2" class="block w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 text-sm" placeholder="Contoh: Tambah modal, Bayar listrik, dll"></textarea>
                    </div>
                    
                    <div class="pt-4 flex justify-end gap-2 border-t mt-4">
                        <button type="button" @click="showModal = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Batal</button>
                        <button type="submit" class="px-4 py-2 text-sm font-bold text-white rounded-lg" :class="type === 'in' ? 'bg-emerald-600 hover:bg-emerald-700' : 'bg-red-600 hover:bg-red-700'">Simpan</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</x-app-layout>