<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\TransactionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['user'])->orderBy('date', 'desc')->orderBy('id', 'desc');

        if ($request->filled('date')) {
            $query->where('date', $request->date);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $transactions = $query->paginate(20)->withQueryString();
        
        return view('admin.transactions.index', compact('transactions'));
    }

    public function show(Transaction $transaction)
    {
        $transaction->load(['transactionDetails.product', 'user']);
        return view('admin.transactions.show', compact('transaction'));
    }

    public function void(Transaction $transaction, TransactionService $service)
    {
        try {
            $service->voidTransaction($transaction, Auth::id());
            return back()->with('success', 'Transaksi berhasil di-void dan stok telah dikembalikan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }
}
