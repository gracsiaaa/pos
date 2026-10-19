<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use App\Models\CashTransaction;
use App\Enums\TransactionStatus;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function getDashboardMetrics()
    {
        $today = Carbon::today()->toDateString();
        $thisMonth = Carbon::today()->startOfMonth()->toDateString();

        $todaySales = Transaction::where('date', $today)
            ->where('status', TransactionStatus::COMPLETED)
            ->sum('total');

        $todayTransactions = Transaction::where('date', $today)
            ->where('status', TransactionStatus::COMPLETED)
            ->count();

        $todayProfit = TransactionDetail::whereHas('transaction', function($q) use ($today) {
            $q->where('date', $today)->where('status', TransactionStatus::COMPLETED);
        })->sum(DB::raw('subtotal - (purchase_price * quantity)'));

        $lowStockProducts = Product::where('is_active', true)
            ->whereColumn('stock', '<=', 'min_stock')
            ->count();

        $todayCashIn = CashTransaction::where('date', $today)->where('type', 'in')->sum('amount');
        $todayCashOut = CashTransaction::where('date', $today)->where('type', 'out')->sum('amount');
        $todayCashBalance = $todayCashIn - $todayCashOut;

        $topProducts = TransactionDetail::select('product_name', DB::raw('SUM(quantity) as total_qty'))
            ->whereHas('transaction', function($q) use ($thisMonth) {
                $q->where('date', '>=', $thisMonth)
                  ->where('status', TransactionStatus::COMPLETED);
            })
            ->groupBy('product_name')
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        return [
            'today_sales' => $todaySales,
            'today_transactions' => $todayTransactions,
            'today_profit' => $todayProfit,
            'low_stock_products' => $lowStockProducts,
            'today_cash_balance' => $todayCashBalance,
            'top_products' => $topProducts
        ];
    }

    public function getSalesChart(int $days = 7)
    {
        $startDate = Carbon::today()->subDays($days - 1)->toDateString();
        
        // Setup empty array with dates
        $chartData = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i)->format('d M');
            $chartData[$date] = [
                'date' => $date,
                'sales' => 0,
                'profit' => 0
            ];
        }

        $salesData = Transaction::select(
                DB::raw('DATE(date) as trx_date'), 
                DB::raw('SUM(total) as total_sales')
            )
            ->where('date', '>=', $startDate)
            ->where('status', TransactionStatus::COMPLETED)
            ->groupBy('trx_date')
            ->get();

        foreach ($salesData as $row) {
            $fmtDate = Carbon::parse($row->trx_date)->format('d M');
            if (isset($chartData[$fmtDate])) {
                $chartData[$fmtDate]['sales'] = (float) $row->total_sales;
            }
        }

        // Get profit (Subtotal - COGS) grouped by transaction date
        $profitData = DB::table('transaction_details')
            ->join('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->select(
                DB::raw('DATE(transactions.date) as trx_date'),
                DB::raw('SUM(transaction_details.subtotal - (transaction_details.purchase_price * transaction_details.quantity)) as total_profit')
            )
            ->where('transactions.date', '>=', $startDate)
            ->where('transactions.status', TransactionStatus::COMPLETED->value)
            ->groupBy(DB::raw('DATE(transactions.date)'))
            ->get();

        foreach ($profitData as $row) {
            $fmtDate = Carbon::parse($row->trx_date)->format('d M');
            if (isset($chartData[$fmtDate])) {
                $chartData[$fmtDate]['profit'] = (float) $row->total_profit;
            }
        }

        return array_values($chartData);
    }
    
    public function getRecentTransactions(int $limit = 5)
    {
        return Transaction::with('user')
            ->orderBy('id', 'desc')
            ->limit($limit)
            ->get();
    }

    public function getSalesReport(Carbon $startDate, Carbon $endDate)
    {
        $startDateStr = $startDate->toDateString();
        $endDateStr = $endDate->toDateString();

        // Global Summary
        $summary = Transaction::whereBetween('date', [$startDateStr, $endDateStr])
            ->where('status', TransactionStatus::COMPLETED)
            ->select(
                DB::raw('COUNT(id) as total_transactions'),
                DB::raw('SUM(total) as total_revenue'),
                DB::raw('SUM(discount) as total_discount'),
                DB::raw('SUM(CASE WHEN payment_method = "cash" THEN total ELSE 0 END) as total_cash'),
                DB::raw('SUM(CASE WHEN payment_method = "debit" THEN total ELSE 0 END) as total_debit'),
                DB::raw('SUM(CASE WHEN payment_method = "qris" THEN total ELSE 0 END) as total_qris')
            )
            ->first();

        // Daily breakdown
        $daily = Transaction::whereBetween('date', [$startDateStr, $endDateStr])
            ->where('status', TransactionStatus::COMPLETED)
            ->select(
                DB::raw('DATE(date) as date'),
                DB::raw('COUNT(id) as transactions'),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->get();

        $daysDiff = $startDate->diffInDays($endDate) + 1;
        $avgDailyRevenue = $daysDiff > 0 ? (($summary->total_revenue ?? 0) / $daysDiff) : 0;

        return [
            'summary' => $summary,
            'daily' => $daily,
            'avg_daily_revenue' => $avgDailyRevenue
        ];
    }

    public function getProfitReport(Carbon $startDate, Carbon $endDate)
    {
        $startDateStr = $startDate->toDateString();
        $endDateStr = $endDate->toDateString();

        // Summary
        $summary = DB::table('transaction_details')
            ->join('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->whereBetween('transactions.date', [$startDateStr, $endDateStr])
            ->where('transactions.status', TransactionStatus::COMPLETED->value)
            ->select(
                DB::raw('SUM(transaction_details.subtotal) as total_revenue'),
                DB::raw('SUM(transaction_details.purchase_price * transaction_details.quantity) as total_cogs')
            )
            ->first();

        $totalRevenue = $summary->total_revenue ?? 0;
        $totalCogs = $summary->total_cogs ?? 0;
        $totalProfit = $totalRevenue - $totalCogs;
        $avgMargin = $totalRevenue > 0 ? ($totalProfit / $totalRevenue) * 100 : 0;

        // Breakdown per product
        $products = DB::table('transaction_details')
            ->join('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->join('products', 'products.id', '=', 'transaction_details.product_id')
            ->leftJoin('categories', 'categories.id', '=', 'products.category_id')
            ->whereBetween('transactions.date', [$startDateStr, $endDateStr])
            ->where('transactions.status', TransactionStatus::COMPLETED->value)
            ->select(
                'transaction_details.product_name',
                'categories.name as category_name',
                DB::raw('SUM(transaction_details.quantity) as qty_sold'),
                DB::raw('SUM(transaction_details.subtotal) as revenue'),
                DB::raw('SUM(transaction_details.purchase_price * transaction_details.quantity) as cogs'),
                DB::raw('SUM(transaction_details.subtotal - (transaction_details.purchase_price * transaction_details.quantity)) as profit')
            )
            ->groupBy('transaction_details.product_id', 'transaction_details.product_name', 'categories.name')
            ->orderByDesc('profit')
            ->get();

        return [
            'total_revenue' => $totalRevenue,
            'total_cogs' => $totalCogs,
            'total_profit' => $totalProfit,
            'avg_margin' => $avgMargin,
            'products' => $products
        ];
    }
}
