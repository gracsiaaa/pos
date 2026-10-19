<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">
            {{ __('Riwayat Transaksi') }}
        </h2>
    </x-slot>

    @if(session('success')) <x-toast type="success" :message="session('success')" /> @endif
    @if(session('error')) <x-toast type="error" :message="session('error')" /> @endif

    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden animate-fade-in">
        
        <form class="p-4 border-b border-gray-100 bg-gray-50/50 flex gap-4 items-end" method="GET">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Tanggal</label>
                <input type="date" name="date" value="{{ request('date') }}" class="rounded-lg border-gray-300 text-sm focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Status</label>
                <select name="status" class="rounded-lg border-gray-300 text-sm focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Semua</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="voided" {{ request('status') == 'voided' ? 'selected' : '' }}>Voided</option>
                </select>
            </div>
            <button type="submit" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-sm font-medium hover:bg-gray-50">Filter</button>
            <a href="{{ url('admin/transactions') }}" class="px-4 py-2 text-primary-600 text-sm font-medium hover:underline">Reset</a>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Invoice / Waktu</th>
                        <th class="px-6 py-4 font-semibold">Kasir</th>
                        <th class="px-6 py-4 font-semibold text-right">Total Transaksi</th>
                        <th class="px-6 py-4 font-semibold text-center">Pembayaran</th>
                        <th class="px-6 py-4 font-semibold text-center">Status</th>
                        <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions as $trx)
                    <tr class="bg-white border-b border-gray-50 hover:bg-gray-50/50">
                        <td class="px-6 py-4">
                            <div class="font-bold text-gray-900">{{ $trx->invoice_number }}</div>
                            <div class="text-xs text-gray-400">{{ $trx->created_at->format('d M Y H:i') }}</div>
                        </td>
                        <td class="px-6 py-4 font-medium text-gray-900">{{ $trx->user->name }}</td>
                        <td class="px-6 py-4 text-right font-bold text-primary-600">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 text-center uppercase text-xs tracking-wider">{{ $trx->payment_method }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($trx->status->value === 'completed')
                                <span class="bg-emerald-50 text-emerald-600 px-2.5 py-1 rounded-md text-xs font-bold ring-1 ring-inset ring-emerald-500/20">Selesai</span>
                            @else
                                <span class="bg-red-50 text-red-600 px-2.5 py-1 rounded-md text-xs font-bold ring-1 ring-inset ring-red-500/20">VOID</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-center gap-2" x-data="{ open: false }">
                                <button @click="open = true" class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 text-xs font-semibold">Detail</button>
                                
                                @if($trx->status->value === 'completed' && Auth::user()->role->name === 'admin')
                                <form action="{{ url('admin/transactions/'.$trx->id.'/void') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="px-3 py-1.5 bg-red-50 text-red-600 hover:bg-red-100 rounded text-xs font-semibold" onclick="return confirm('Yakin void transaksi ini? Stok dan kas akan dikembalikan.')">Void</button>
                                </form>
                                @endif

                                <!-- Modal Detail (Simplified for inline usage) -->
                                <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-gray-900/50 backdrop-blur-sm text-left" style="display: none;">
                                    <div @click.outside="open = false" class="bg-white rounded-2xl w-full max-w-lg overflow-hidden shadow-xl animate-slide-up">
                                        <div class="px-6 py-4 border-b flex justify-between">
                                            <h3 class="font-bold text-lg">Detail Transaksi {{ $trx->invoice_number }}</h3>
                                            <button @click="open = false" class="text-gray-400 hover:text-gray-600">&times;</button>
                                        </div>
                                        <div class="p-6 text-sm">
                                            <div class="grid grid-cols-2 gap-4 mb-4 text-gray-600">
                                                <div><span class="block text-xs">Kasir</span> <span class="font-bold text-gray-900">{{ $trx->user->name }}</span></div>
                                                <div><span class="block text-xs">Tanggal</span> <span class="font-bold text-gray-900">{{ $trx->created_at->format('d/m/Y H:i') }}</span></div>
                                            </div>
                                            <div class="border rounded-lg p-3 space-y-2 bg-gray-50">
                                                @foreach($trx->transactionDetails as $detail)
                                                <div class="flex justify-between border-b border-dashed pb-2 last:border-0 last:pb-0">
                                                    <div>
                                                        <div class="font-semibold">{{ $detail->product_name }}</div>
                                                        <div class="text-xs">{{ $detail->quantity }} x {{ number_format($detail->unit_price, 0, ',', '.') }}</div>
                                                    </div>
                                                    <div class="font-bold">{{ number_format($detail->subtotal, 0, ',', '.') }}</div>
                                                </div>
                                                @endforeach
                                            </div>
                                            <div class="mt-4 flex justify-between font-bold text-lg border-t pt-4">
                                                <span>TOTAL</span>
                                                <span class="text-primary-600">Rp {{ number_format($trx->total, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="flex justify-between text-gray-500 text-sm mt-1">
                                                <span>Bayar ({{ strtoupper($trx->payment_method) }})</span>
                                                <span>Rp {{ number_format($trx->paid_amount, 0, ',', '.') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="px-6 py-10 text-center text-gray-500">Belum ada transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($transactions->hasPages())
        <div class="p-4 border-t border-gray-100 bg-gray-50">{{ $transactions->links() }}</div>
        @endif
    </div>
</x-app-layout>