@extends('layouts.app')

@section('title', 'Dashboard')
@section('page_title', 'Dashboard')

@section('content')
<div class="space-y-8">
    <div class="relative bg-gradient-to-r from-blue-600 to-indigo-600 rounded-3xl p-6 md:p-8 text-white shadow-xl shadow-blue-500/10 overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#fff_1px,transparent_1px)] [background-size:16px_16px]"></div>
        <div class="absolute -right-12 -bottom-12 w-48 h-48 rounded-full bg-white/10 blur-2xl"></div>
        <div class="absolute right-1/3 -top-12 w-32 h-32 rounded-full bg-white/5 blur-xl"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
            <div>
                <h3 class="text-xl md:text-2xl font-extrabold tracking-wide">Selamat datang di Kasirku, {{ Auth::user()->name }}! 👋</h3>
                <p class="text-blue-100 text-xs md:text-sm mt-1.5 font-medium">Semua metrik dan laporan toko Anda telah terintegrasi secara otomatis hari ini.</p>
            </div>
            <div>
                <a href="{{ route('cashier.index') }}" class="inline-flex items-center gap-2 px-5 py-3 bg-white text-blue-700 hover:bg-blue-50 font-bold text-xs md:text-sm rounded-2xl shadow-lg transition-all duration-200">
                    <i data-lucide="shopping-cart" class="w-4.5 h-4.5"></i>
                    <span>Buka Kasir Baru</span>
                </a>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-5 hover:shadow-md transition-shadow">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 shadow-inner">
                <i data-lucide="banknote" class="w-7 h-7"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs text-slate-500 font-semibold tracking-wider uppercase">Penjualan Hari Ini</p>
                <h4 class="text-lg md:text-xl font-bold text-slate-800 mt-1 truncate">Rp {{ number_format($salesToday, 0, ',', '.') }}</h4>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-5 hover:shadow-md transition-shadow">
            <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 shadow-inner">
                <i data-lucide="shopping-bag" class="w-7 h-7"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs text-slate-500 font-semibold tracking-wider uppercase">Transaksi Hari Ini</p>
                <h4 class="text-lg md:text-xl font-bold text-slate-800 mt-1 truncate">{{ number_format($transactionsTodayCount, 0, ',', '.') }}</h4>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-5 hover:shadow-md transition-shadow">
            <div class="w-14 h-14 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 shadow-inner">
                <i data-lucide="package" class="w-7 h-7"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs text-slate-500 font-semibold tracking-wider uppercase">Total Produk</p>
                <h4 class="text-lg md:text-xl font-bold text-slate-800 mt-1 truncate">{{ number_format($totalProductsCount, 0, ',', '.') }}</h4>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-5 hover:shadow-md transition-shadow">
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 shadow-inner">
                <i data-lucide="line-chart" class="w-7 h-7"></i>
            </div>
            <div class="min-w-0">
                <p class="text-xs text-slate-500 font-semibold tracking-wider uppercase">Pendapatan Bulan Ini</p>
                <h4 class="text-lg md:text-xl font-bold text-slate-800 mt-1 truncate">Rp {{ number_format($salesThisMonth, 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm lg:col-span-2">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-base font-bold text-slate-800">Tren Pendapatan</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Grafik omset penjualan harian selama bulan {{ $monthName }}</p>
                </div>
                <span class="text-xs font-semibold px-3 py-1 bg-blue-50 text-blue-600 rounded-lg">{{ $monthName }}</span>
            </div>
            <div class="relative h-72">
                <canvas id="salesTrendChart"></canvas>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex flex-col">
            <div class="mb-5">
                <h3 class="text-base font-bold text-slate-800">Transaksi Terbaru</h3>
                <p class="text-[11px] text-slate-500 mt-0.5">Daftar transaksi kasir terupdate</p>
            </div>
            
            <div class="flex-1 overflow-y-auto space-y-4 max-h-[288px] pr-1">
                @forelse($recentTransactions as $tx)
                    <div class="flex items-center justify-between p-3 rounded-2xl border border-slate-50 hover:border-slate-100 hover:bg-slate-50/50 transition-colors">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                                <i data-lucide="receipt" class="w-5 h-5"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-slate-800 truncate">{{ $tx->invoice_number }}</p>
                                <p class="text-[10px] text-slate-500 mt-0.5">{{ $tx->created_at->diffForHumans() }} &bull; {{ $tx->user->name }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-xs font-bold text-slate-800">Rp{{ number_format($tx->total_price, 0, ',', '.') }}</p>
                            <span class="inline-block text-[9px] font-semibold bg-emerald-50 text-emerald-600 px-1.5 py-0.5 rounded-md mt-0.5">Sukses</span>
                        </div>
                    </div>
                @empty
                    <div class="h-full flex flex-col items-center justify-center text-center py-10">
                        <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 mb-2">
                            <i data-lucide="inbox" class="w-6 h-6"></i>
                        </div>
                        <p class="text-xs font-semibold text-slate-500">Belum ada transaksi hari ini</p>
                    </div>
                @endforelse
            </div>
            
            @if($recentTransactions->count() > 0)
                <a href="{{ route('reports.index') }}" class="mt-4 text-center text-xs font-semibold text-blue-600 hover:text-blue-700 hover:underline flex items-center justify-center gap-1">
                    <span>Lihat Laporan Detail</span>
                    <i data-lucide="chevron-right" class="w-4 h-4"></i>
                </a>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('salesTrendChart').getContext('2d');
        
        const labels = {!! $chartLabels !!};
        const data = {!! $chartData !!};
        
        const gradient = ctx.createLinearGradient(0, 0, 0, 250);
        gradient.addColorStop(0, 'rgba(59, 130, 246, 0.35)');
        gradient.addColorStop(1, 'rgba(59, 130, 246, 0.00)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels.map(day => `Tgl ${day}`),
                datasets: [{
                    label: 'Omset Penjualan (Rp)',
                    data: data,
                    borderColor: '#2563eb',
                    borderWidth: 3.5,
                    backgroundColor: gradient,
                    fill: true,
                    tension: 0.35,
                    pointBackgroundColor: '#2563eb',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6,
                    shadowColor: 'rgba(37, 99, 235, 0.25)',
                    shadowBlur: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        padding: 12,
                        backgroundColor: '#0f172a',
                        titleFont: {
                            family: 'Plus Jakarta Sans',
                            size: 11,
                            weight: 'bold'
                        },
                        bodyFont: {
                            family: 'Plus Jakarta Sans',
                            size: 12
                        },
                        callbacks: {
                            label: function(context) {
                                let value = context.raw;
                                return ' Pendapatan: Rp ' + new Intl.NumberFormat('id-ID').format(value);
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        grid: {
                            color: '#f1f5f9'
                        },
                        ticks: {
                            font: {
                                family: 'Plus Jakarta Sans',
                                size: 10
                            },
                            color: '#64748b',
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return 'Rp ' + (value / 1000000) + 'jt';
                                } else if (value >= 1000) {
                                    return 'Rp ' + (value / 1000) + 'rb';
                                }
                                return 'Rp ' + value;
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                family: 'Plus Jakarta Sans',
                                size: 9
                            },
                            color: '#64748b',
                            maxTicksLimit: 15
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
