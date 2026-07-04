<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $salesToday = Transaction::whereDate('created_at', $today)->sum('total_price');
        $transactionsTodayCount = Transaction::whereDate('created_at', $today)->count();
        $totalProductsCount = Product::count();
        $salesThisMonth = Transaction::whereBetween('created_at', [$startOfMonth, $endOfMonth])->sum('total_price');

        $recentTransactions = Transaction::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $daysInMonth = Carbon::now()->daysInMonth;
        $chartLabels = [];
        $chartData = [];

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dateStr = Carbon::now()->day($day)->format('Y-m-d');
            $chartLabels[] = $day;
            $chartData[$dateStr] = 0;
        }

        $monthlySalesData = Transaction::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_price) as total'))
            ->groupBy('date')
            ->get();

        foreach ($monthlySalesData as $data) {
            if (isset($chartData[$data->date])) {
                $chartData[$data->date] = (float) $data->total;
            }
        }

        return view('dashboard', [
            'salesToday' => $salesToday,
            'transactionsTodayCount' => $transactionsTodayCount,
            'totalProductsCount' => $totalProductsCount,
            'salesThisMonth' => $salesThisMonth,
            'recentTransactions' => $recentTransactions,
            'chartLabels' => json_encode($chartLabels),
            'chartData' => json_encode(array_values($chartData)),
            'monthName' => Carbon::now()->translatedFormat('F Y'),
        ]);
    }
}
