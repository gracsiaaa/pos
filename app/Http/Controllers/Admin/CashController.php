<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CashTransaction;
use App\Services\CashService;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCashRequest;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CashController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->filled('date') ? Carbon::parse($request->date)->toDateString() : Carbon::today()->toDateString();
        
        $cashIn = CashTransaction::where('date', $date)->where('type', 'in')->sum('amount');
        $cashOut = CashTransaction::where('date', $date)->where('type', 'out')->sum('amount');
        $balance = $cashIn - $cashOut;

        $transactions = CashTransaction::with('user')
            ->where('date', $date)
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.cash.index', compact('transactions', 'date', 'cashIn', 'cashOut', 'balance'));
    }

    public function store(StoreCashRequest $request, CashService $service)
    {
        try {
            $data = $request->validated();
            if ($data['type'] === 'in') {
                $service->recordIn(Auth::id(), $data['amount'], $data['description'], 'manual_entry');
            } else {
                $service->recordOut(Auth::id(), $data['amount'], $data['description'], 'manual_entry');
            }
            return back()->with('success', 'Catatan kas berhasil ditambahkan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
