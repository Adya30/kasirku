@extends('layouts.app')

@section('title', 'Laporan Penjualan')
@section('page_title', 'Laporan Penjualan')

@section('content')
<div class="space-y-6">
    
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex flex-col md:flex-row md:items-end justify-between gap-6 print:hidden">
        <form action="{{ route('reports.index') }}" method="GET" class="flex flex-col sm:flex-row items-stretch sm:items-end gap-4 flex-1">
            <div class="space-y-1.5 shrink-0">
                <label for="type" class="text-xs font-semibold text-slate-700 block">Jenis Laporan</label>
                <select name="type" id="type" onchange="this.form.submit()"
                        class="w-full sm:w-44 px-3 py-2.5 bg-slate-50 border border-slate-200 text-slate-800 text-xs rounded-xl focus:border-blue-500 focus:outline-none">
                    <option value="daily" {{ $type === 'daily' ? 'selected' : '' }}>Laporan Harian</option>
                    <option value="monthly" {{ $type === 'monthly' ? 'selected' : '' }}>Laporan Bulanan</option>
                </select>
            </div>

            @if($type === 'daily')
                <div class="space-y-1.5 flex-1 max-w-xs">
                    <label for="date" class="text-xs font-semibold text-slate-700 block">Pilih Tanggal</label>
                    <input type="date" name="date" id="date" value="{{ $date }}"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 text-slate-800 text-xs rounded-xl focus:border-blue-500 focus:outline-none">
                </div>
            @else
                <div class="space-y-1.5 flex-1 max-w-xs">
                    <label for="month" class="text-xs font-semibold text-slate-700 block">Pilih Bulan</label>
                    <input type="month" name="month" id="month" value="{{ $month }}"
                           class="w-full px-3 py-2 bg-slate-50 border border-slate-200 text-slate-800 text-xs rounded-xl focus:border-blue-500 focus:outline-none">
                </div>
            @endif

            <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl transition-all shadow-md shadow-blue-500/10 flex items-center justify-center gap-1.5 shrink-0">
                <i data-lucide="filter" class="w-4 h-4"></i>
                <span>Terapkan</span>
            </button>
        </form>

        <div class="flex items-center gap-2 shrink-0">
            <button onclick="window.print()" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl border border-slate-200 transition-all flex items-center justify-center gap-1.5">
                <i data-lucide="printer" class="w-4.5 h-4.5 text-blue-600"></i>
                <span>Cetak Laporan</span>
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-5 hover:shadow-md transition-shadow">
            <div class="w-14 h-14 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shrink-0 shadow-inner print:bg-slate-100">
                <i data-lucide="wallet" class="w-7 h-7"></i>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-semibold tracking-wider uppercase">Total Penjualan</p>
                <h4 class="text-xl md:text-2xl font-black text-slate-800 mt-1">Rp {{ number_format($summary['total_sales'], 0, ',', '.') }}</h4>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm flex items-center gap-5 hover:shadow-md transition-shadow">
            <div class="w-14 h-14 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 shadow-inner print:bg-slate-100">
                <i data-lucide="shopping-bag" class="w-7 h-7"></i>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-semibold tracking-wider uppercase">Total Transaksi</p>
                <h4 class="text-xl md:text-2xl font-black text-slate-800 mt-1">{{ number_format($summary['total_transactions'], 0, ',', '.') }}</h4>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-slate-800">
                    {{ $type === 'daily' ? 'Rincian Penjualan Harian' : 'Rangkuman Penjualan Bulanan' }}
                </h3>
                <p class="text-[11px] text-slate-500 mt-0.5">
                    Periode: {{ $type === 'daily' ? Carbon\Carbon::parse($date)->translatedFormat('d F Y') : Carbon\Carbon::parse($month . '-01')->translatedFormat('F Y') }}
                </p>
            </div>
            <span class="hidden print:inline-block text-xs font-bold text-slate-500 border border-slate-200 px-3 py-1 rounded-xl">Laporan Kasirku</span>
        </div>

        <div class="overflow-x-auto">
            @if($type === 'daily')
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-xs font-bold uppercase tracking-wider">
                            <th class="py-4 px-6">No. Invoice</th>
                            <th class="py-4 px-6">Waktu</th>
                            <th class="py-4 px-6">Kasir</th>
                            <th class="py-4 px-6">Detail Pembelian</th>
                            <th class="py-4 px-6 text-right">Total Transaksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700 text-sm">
                        @forelse($transactions as $tx)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-6 font-bold text-slate-800 font-mono text-xs">{{ $tx->invoice_number }}</td>
                                <td class="py-4 px-6 text-xs text-slate-500">{{ $tx->created_at->format('H:i:s') }}</td>
                                <td class="py-4 px-6 font-semibold">{{ $tx->user ? $tx->user->name : '-' }}</td>
                                <td class="py-4 px-6 text-xs">
                                    <div class="space-y-1">
                                        @foreach($tx->details as $d)
                                            <div class="flex items-center gap-1.5 text-slate-600">
                                                <span class="font-bold text-slate-800">{{ $d->quantity }}x</span>
                                                <span class="truncate max-w-[200px]">{{ $d->product ? $d->product->name : 'Produk' }}</span>
                                                <span class="text-slate-400">(@ Rp{{ number_format($d->price, 0, ',', '.') }})</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-right font-extrabold text-slate-800">
                                    Rp {{ number_format($tx->total_price, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-16 text-center text-slate-400">
                                    <div class="w-14 h-14 rounded-full bg-slate-50 flex items-center justify-center mx-auto text-slate-300 mb-3 border border-slate-100">
                                        <i data-lucide="receipt" class="w-6 h-6"></i>
                                    </div>
                                    <p class="text-sm font-semibold">Tidak ada transaksi pada tanggal ini</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @else
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100 text-slate-500 text-xs font-bold uppercase tracking-wider">
                            <th class="py-4 px-6">Tanggal</th>
                            <th class="py-4 px-6 text-center">Jumlah Transaksi</th>
                            <th class="py-4 px-6 text-right">Total Pendapatan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-slate-700 text-sm">
                        @forelse($monthlyGrouped as $row)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-6 font-bold text-slate-800">{{ Carbon\Carbon::parse($row->date)->translatedFormat('d F Y') }}</td>
                                <td class="py-4 px-6 text-center font-semibold text-slate-600">{{ number_format($row->transaction_count, 0, ',', '.') }} Transaksi</td>
                                <td class="py-4 px-6 text-right font-extrabold text-slate-800">
                                    Rp {{ number_format($row->total_sales, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-16 text-center text-slate-400">
                                    <div class="w-14 h-14 rounded-full bg-slate-50 flex items-center justify-center mx-auto text-slate-300 mb-3 border border-slate-100">
                                        <i data-lucide="calendar" class="w-6 h-6"></i>
                                    </div>
                                    <p class="text-sm font-semibold">Tidak ada data transaksi pada bulan ini</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</div>

<style>
    @media print {
        body {
            background-color: white !important;
            padding: 0 !important;
            margin: 0 !important;
        }
        main {
            padding: 0 !important;
            margin: 0 !important;
        }
        .print\:hidden {
            display: none !important;
        }
        header {
            display: none !important;
        }
        aside {
            display: none !important;
        }
        .bg-white {
            border: none !important;
            box-shadow: none !important;
        }
        .shadow-sm, .shadow-md, .shadow-xl {
            box-shadow: none !important;
        }
        table {
            border-bottom: 2px solid #000;
        }
        thead tr {
            border-bottom: 2px solid #000;
            background-color: #f8fafc !important;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }
    }
</style>
@endsection
