<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $type = $request->get('type', 'daily');
        $date = $request->get('date', Carbon::today()->format('Y-m-d'));
        $month = $request->get('month', Carbon::today()->format('Y-m'));

        $transactions = collect();
        $summary = [
            'total_sales' => 0,
            'total_transactions' => 0,
        ];
        
        $monthlyGrouped = collect();

        if ($type === 'daily') {
            $transactions = Transaction::with('user', 'details.product')
                ->whereDate('created_at', $date)
                ->orderBy('created_at', 'desc')
                ->get();

            $summary['total_sales'] = $transactions->sum('total_price');
            $summary['total_transactions'] = $transactions->count();
        } else {
            $parsedMonth = Carbon::parse($month . '-01');
            $startOfMonth = $parsedMonth->copy()->startOfMonth();
            $endOfMonth = $parsedMonth->copy()->endOfMonth();

            $monthlyGrouped = Transaction::whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COUNT(id) as transaction_count'),
                    DB::raw('SUM(total_price) as total_sales')
                )
                ->groupBy('date')
                ->orderBy('date', 'asc')
                ->get();

            $summary['total_sales'] = $monthlyGrouped->sum('total_sales');
            $summary['total_transactions'] = $monthlyGrouped->sum('transaction_count');
        }

        return view('reports.index', [
            'type' => $type,
            'date' => $date,
            'month' => $month,
            'transactions' => $transactions,
            'monthlyGrouped' => $monthlyGrouped,
            'summary' => $summary,
        ]);
    }
}
