<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight">Laporan Penjualan Harian</h2>
    </x-slot>

    <!-- Filter Bar -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6 flex flex-col sm:flex-row gap-4 justify-between items-center animate-fade-in">
        <form method="GET" class="flex flex-wrap items-end gap-3 w-full sm:w-auto">
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="rounded-lg border-gray-300 text-sm focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div>
                <label class="block text-xs font-medium text-gray-500 mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="rounded-lg border-gray-300 text-sm focus:ring-primary-500 focus:border-primary-500">
            </div>
            <button type="submit" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm h-[38px] flex items-center">
                Terapkan Filter
            </button>
            <a href="{{ route('admin.reports.sales') }}" class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-sm font-medium transition-colors h-[38px] flex items-center">Reset</a>
        </form>

        <button onclick="window.print()" class="hidden sm:flex px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-lg text-sm font-medium transition-colors shadow-sm items-center">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg> Cetak PDF
        </button>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-500 rounded-2xl shadow-sm text-white p-5 animate-fade-in" style="animation-delay: 0.1s">
            <div class="text-blue-100 text-xs font-semibold uppercase tracking-wider mb-1">Total Pendapatan</div>
            <div class="text-2xl font-bold mb-2">Rp {{ number_format($summary->total_revenue ?? 0, 0, ',', '.') }}</div>
            <div class="text-blue-100 text-xs flex justify-between">
                <span>Trx: {{ $summary->total_transactions ?? 0 }}</span>
                <span>Rata-rata: Rp {{ number_format($avg_daily_revenue, 0, ',', '.') }}/hari</span>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 animate-fade-in" style="animation-delay: 0.2s">
            <div class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-1 flex items-center"><span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span> Via Tunai (Cash)</div>
            <div class="text-xl font-bold text-gray-900 mt-2">Rp {{ number_format($summary->total_cash ?? 0, 0, ',', '.') }}</div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 animate-fade-in" style="animation-delay: 0.3s">
            <div class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-1 flex items-center"><span class="w-2 h-2 rounded-full bg-blue-500 mr-2"></span> Via QRIS</div>
            <div class="text-xl font-bold text-gray-900 mt-2">Rp {{ number_format($summary->total_qris ?? 0, 0, ',', '.') }}</div>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 animate-fade-in" style="animation-delay: 0.4s">
            <div class="text-gray-500 text-xs font-semibold uppercase tracking-wider mb-1 flex items-center"><span class="w-2 h-2 rounded-full bg-purple-500 mr-2"></span> Via Kartu Debit</div>
            <div class="text-xl font-bold text-gray-900 mt-2">Rp {{ number_format($summary->total_debit ?? 0, 0, ',', '.') }}</div>
        </div>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden animate-fade-in" style="animation-delay: 0.5s">
        <div class="p-5 border-b border-gray-100 bg-gray-50/50">
            <h3 class="font-bold text-gray-800">Rincian Harian</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 font-semibold">Tanggal</th>
                        <th class="px-6 py-4 font-semibold text-center">Jml Transaksi</th>
                        <th class="px-6 py-4 font-semibold text-right">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($daily as $day)
                    <tr class="bg-white border-b border-gray-50 hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 font-medium text-gray-900">{{ \Carbon\Carbon::parse($day->date)->translatedFormat('l, d F Y') }}</td>
                        <td class="px-6 py-4 text-center font-bold text-gray-700">{{ $day->transactions }}</td>
                        <td class="px-6 py-4 text-right font-bold text-primary-600">Rp {{ number_format($day->revenue, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-6 py-12 text-center text-gray-500">
                            Tidak ada data penjualan pada rentang tanggal tersebut.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if(count($daily) > 0)
                <tfoot class="bg-gray-50 font-bold text-gray-900">
                    <tr>
                        <td class="px-6 py-4 text-right uppercase text-xs">Total Keseluruhan</td>
                        <td class="px-6 py-4 text-center">{{ $summary->total_transactions ?? 0 }}</td>
                        <td class="px-6 py-4 text-right text-primary-700 text-lg">Rp {{ number_format($summary->total_revenue ?? 0, 0, ',', '.') }}</td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>
    </div>

    <!-- Print styling -->
    <style>
        @media print {
            aside, header, form, button { display: none !important; }
            main { padding: 0 !important; margin: 0 !important; }
            .bg-white { box-shadow: none !important; border: none !important; }
            body { background: white !important; }
            .shadow-sm { box-shadow: none !important; }
        }
    </style>
</x-app-layout>