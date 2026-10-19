<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Dashboard') }}
            </h2>
            <div class="text-sm text-gray-500 font-medium bg-gray-100 px-4 py-2 rounded-lg">
                {{ now()->translatedFormat('l, d F Y') }}
            </div>
        </div>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Pendapatan Hari Ini -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center hover:scale-[1.02] transition-transform animate-fade-in" style="animation-delay: 0.1s">
            <div class="p-3 rounded-xl bg-blue-50 text-primary-600 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div>
                <div class="text-sm font-medium text-gray-500">Pendapatan Hari Ini</div>
                <div class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($metrics['today_sales'], 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- Profit Hari Ini -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center hover:scale-[1.02] transition-transform animate-fade-in" style="animation-delay: 0.2s">
            <div class="p-3 rounded-xl bg-emerald-50 text-emerald-600 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
            </div>
            <div>
                <div class="text-sm font-medium text-gray-500">Profit Hari Ini</div>
                <div class="text-2xl font-bold text-emerald-600 mt-1">Rp {{ number_format($metrics['today_profit'], 0, ',', '.') }}</div>
            </div>
        </div>

        <!-- Transaksi -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center hover:scale-[1.02] transition-transform animate-fade-in" style="animation-delay: 0.3s">
            <div class="p-3 rounded-xl bg-purple-50 text-purple-600 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
            </div>
            <div>
                <div class="text-sm font-medium text-gray-500">Total Transaksi</div>
                <div class="text-2xl font-bold text-gray-900 mt-1">{{ $metrics['today_transactions'] }}</div>
            </div>
        </div>
        
        <!-- Stok Menipis -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex items-center border-l-4 border-l-amber-500 hover:scale-[1.02] transition-transform animate-fade-in" style="animation-delay: 0.4s">
            <div class="p-3 rounded-xl bg-amber-50 text-amber-600 mr-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div>
                <div class="text-sm font-medium text-gray-500">Stok Menipis</div>
                <div class="text-2xl font-bold text-amber-600 mt-1">{{ $metrics['low_stock_products'] }} <span class="text-sm text-gray-400 font-normal">Item</span></div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 animate-fade-in" style="animation-delay: 0.5s">
        <!-- Chart Area -->
        <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-bold text-gray-800">Trend Keuangan (7 Hari Terakhir)</h3>
            </div>
            <div class="relative h-72 w-full">
                <canvas id="financeChart"></canvas>
            </div>
        </div>

        <div class="flex flex-col gap-6">
            <!-- Arus Kas Hari Ini -->
            <div class="bg-gray-900 rounded-2xl shadow-sm p-6 text-white relative overflow-hidden">
                <svg class="absolute -right-4 -bottom-4 w-32 h-32 opacity-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <div class="relative z-10">
                    <h3 class="font-bold text-gray-200 mb-4">Arus Kas Hari Ini</h3>
                    <div class="text-3xl font-black text-white mb-6">
                        Rp {{ number_format($metrics['today_cash_balance'], 0, ',', '.') }}
                        <div class="text-xs font-normal text-gray-400 mt-1 uppercase tracking-wider">Saldo Bersih</div>
                    </div>
                    <div class="space-y-3 text-sm">
                        <div class="flex justify-between items-center pb-2 border-b border-gray-700">
                            <span class="flex items-center text-gray-300"><span class="w-2 h-2 rounded-full bg-emerald-500 mr-2"></span> Kas Masuk</span>
                            <span class="font-bold text-emerald-400">Rp {{ number_format(\App\Models\CashTransaction::where('date', date('Y-m-d'))->where('type', 'in')->sum('amount'), 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="flex items-center text-gray-300"><span class="w-2 h-2 rounded-full bg-red-500 mr-2"></span> Kas Keluar</span>
                            <span class="font-bold text-red-400">Rp {{ number_format(\App\Models\CashTransaction::where('date', date('Y-m-d'))->where('type', 'out')->sum('amount'), 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Produk Terlaris (Right 1/3) -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 flex-1">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-bold text-gray-800">Top 5 Produk</h3>
                    <span class="text-xs font-medium text-gray-500">Bulan Ini</span>
                </div>
                
                <div class="space-y-4">
                    @forelse($metrics['top_products'] as $index => $tp)
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="flex items-center justify-center w-8 h-8 rounded-full {{ $index === 0 ? 'bg-amber-100 text-amber-700 font-bold' : ($index === 1 ? 'bg-gray-100 text-gray-700 font-bold' : ($index === 2 ? 'bg-orange-100 text-orange-700 font-bold' : 'bg-gray-50 text-gray-500 text-sm')) }}">
                                {{ $index + 1 }}
                            </div>
                            <p class="text-sm font-medium text-gray-800 line-clamp-1" title="{{ $tp->product_name }}">{{ $tp->product_name }}</p>
                        </div>
                        <div class="text-sm font-bold text-primary-600 bg-primary-50 px-2 py-1 rounded-md">{{ $tp->total_qty }}</div>
                    </div>
                    @empty
                    <div class="text-center py-6 text-gray-500 text-sm">Belum ada data penjualan.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Transaksi Terakhir -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 mb-8 animate-fade-in" style="animation-delay: 0.6s">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-gray-800">Transaksi Terbaru</h3>
            <a href="{{ url('admin/transactions') }}" class="text-sm font-medium text-primary-600 hover:text-primary-800">Lihat Semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-4 py-3">Invoice</th>
                        <th class="px-4 py-3">Waktu</th>
                        <th class="px-4 py-3">Kasir</th>
                        <th class="px-4 py-3 text-center">Status</th>
                        <th class="px-4 py-3 text-right">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTransactions as $trx)
                    <tr class="border-b border-gray-50 hover:bg-gray-50/50">
                        <td class="px-4 py-3 font-bold text-gray-900">{{ $trx->invoice_number }}</td>
                        <td class="px-4 py-3">{{ $trx->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-4 py-3">{{ $trx->user->name }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($trx->status->value === 'completed')
                                <span class="bg-emerald-50 text-emerald-600 px-2.5 py-1 rounded-md text-xs font-bold ring-1 ring-inset ring-emerald-500/20">Selesai</span>
                            @else
                                <span class="bg-red-50 text-red-600 px-2.5 py-1 rounded-md text-xs font-bold ring-1 ring-inset ring-red-500/20">VOID</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right font-bold text-primary-600">Rp {{ number_format($trx->total, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="px-4 py-6 text-center text-gray-500">Belum ada transaksi.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Chart.js Setup -->
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const chartData = @json($chartData);
            
            const labels = chartData.map(d => d.date);
            const salesData = chartData.map(d => d.sales);
            const profitData = chartData.map(d => d.profit);

            const ctx = document.getElementById('financeChart').getContext('2d');
            
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Pendapatan (Sales)',
                            data: salesData,
                            borderColor: '#3b82f6', // blue-500
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: true,
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#3b82f6',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        },
                        {
                            label: 'Keuntungan (Profit)',
                            data: profitData,
                            borderColor: '#10b981', // emerald-500
                            backgroundColor: 'transparent',
                            borderWidth: 3,
                            tension: 0.4,
                            fill: false,
                            borderDash: [5, 5],
                            pointBackgroundColor: '#ffffff',
                            pointBorderColor: '#10b981',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            align: 'end',
                            labels: { usePointStyle: true, boxWidth: 8 }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(17, 24, 39, 0.9)',
                            titleFont: { size: 13, family: "'Inter', sans-serif" },
                            bodyFont: { size: 13, family: "'Inter', sans-serif" },
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) { label += ': '; }
                                    if (context.parsed.y !== null) {
                                        label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(context.parsed.y);
                                    }
                                    return label;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: { display: false, drawBorder: false },
                            ticks: { font: { family: "'Inter', sans-serif" }, color: '#6b7280' }
                        },
                        y: {
                            grid: { color: '#f3f4f6', drawBorder: false },
                            ticks: {
                                font: { family: "'Inter', sans-serif" }, 
                                color: '#6b7280',
                                callback: function(value) {
                                    if(value >= 1000000) return 'Rp ' + (value / 1000000) + 'M';
                                    if(value >= 1000) return 'Rp ' + (value / 1000) + 'K';
                                    return 'Rp ' + value;
                                }
                            },
                            beginAtZero: true
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
