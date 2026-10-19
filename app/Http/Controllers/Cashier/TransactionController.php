<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Services\TransactionService;
use Illuminate\Support\Facades\Auth;
use Exception;

class TransactionController extends Controller
{
    protected TransactionService $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function index()
    {
        $products = Product::where('is_active', true)->where('stock', '>', 0)->get();
        return view('cashier.pos.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1', // Mencegah quantity negatif / nol
            'items.*.discount' => 'nullable|numeric|min:0',
            'paid_amount' => 'required|numeric|min:0', // Mencegah minus
            'payment_method' => 'required|in:cash,debit,credit,qris',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
            'note' => 'nullable|string'
        ]);

        try {
            $transaction = $this->transactionService->processTransaction(
                userId: Auth::id(),
                items: $request->items,
                paidAmount: $request->paid_amount,
                paymentMethod: $request->payment_method,
                discount: $request->discount ?? 0,
                tax: $request->tax ?? 0,
                note: $request->note
            );

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil.',
                'transaction_id' => $transaction->id,
                'invoice_number' => $transaction->invoice_number,
                'change_amount' => $transaction->change_amount,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 400);
        }
    }
}
