<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReportService;
use Illuminate\Support\Facades\Auth;
use App\Enums\RoleEnum;

class DashboardController extends Controller
{
    public function index(ReportService $reportService)
    {
        $user = Auth::user();

        if ($user->role->name === RoleEnum::CASHIER->value) {
            return redirect()->route('pos.index');
        }

        $metrics = $reportService->getDashboardMetrics();
        $chartData = $reportService->getSalesChart(7);
        $recentTransactions = $reportService->getRecentTransactions(5);

        return view('dashboard', compact('metrics', 'chartData', 'recentTransactions'));
    }
}
