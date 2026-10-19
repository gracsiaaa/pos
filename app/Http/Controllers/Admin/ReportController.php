<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\ReportService;
use App\Http\Requests\ReportFilterRequest;

class ReportController extends Controller
{
    protected ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function sales(ReportFilterRequest $request)
    {
        $startDate = $request->getStartDate();
        $endDate = $request->getEndDate();
        
        $data = $this->reportService->getSalesReport($startDate, $endDate);
        
        return view('admin.reports.sales', array_merge($data, [
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d')
        ]));
    }

    public function profit(ReportFilterRequest $request)
    {
        $startDate = $request->getStartDate();
        $endDate = $request->getEndDate();
        
        $data = $this->reportService->getProfitReport($startDate, $endDate);
        
        return view('admin.reports.profit', array_merge($data, [
            'startDate' => $startDate->format('Y-m-d'),
            'endDate' => $endDate->format('Y-m-d')
        ]));
    }
}
