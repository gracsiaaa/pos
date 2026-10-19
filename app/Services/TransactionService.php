<?php

namespace App\Services;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\Product;
use App\Enums\TransactionStatus;
use App\Enums\StockMovementType;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Str;
use Carbon\Carbon;

class TransactionService
{
    protected StockService $stockService;
    protected CashService $cashService;

    public function __construct(StockService $stockService, CashService $cashService)
    {
        $this->stockService = $stockService;
        $this->cashService = $cashService;
    }

    /**
     * Process a new POS transaction.
     * Items format: [['product_id' => 1, 'quantity' => 2, 'discount' => 0], ...]
     */
    public function processTransaction(int $userId, array $items, float $paidAmount, string $paymentMethod = 'cash', float $discount = 0, float $tax = 0, string $note = null): Transaction
    {
        if (empty($items)) {
            throw new Exception("Transaction items cannot be empty.");
        }

        return DB::transaction(function () use ($userId, $items, $paidAmount, $paymentMethod, $discount, $tax, $note) {
            $subtotal = 0;
            $date = now();
            
            $invoiceNumber = $this->generateInvoiceNumber($date);

            $transaction = Transaction::create([
                'user_id' => $userId,
                'invoice_number' => $invoiceNumber,
                'date' => $date->toDateString(),
                'subtotal' => 0,
                'discount' => $discount,
                'tax' => $tax,
                'total' => 0,
                'paid_amount' => $paidAmount,
                'change_amount' => 0,
                'payment_method' => $paymentMethod,
                'status' => TransactionStatus::COMPLETED,
                'note' => $note,
            ]);

            foreach ($items as $item) {
                $product = Product::where('id', $item['product_id'])->lockForUpdate()->firstOrFail();
                
                $qty = $item['quantity'];
                
                // Mencegah pembelian melebihi stok yang ada
                if ($product->stock < $qty) {
                    throw new Exception("Stok tidak mencukupi untuk produk: {$product->name}. Sisa: {$product->stock}");
                }

                $itemDiscount = $item['discount'] ?? 0;
                $itemSubtotal = ($product->selling_price * $qty) - $itemDiscount;
                
                $subtotal += $itemSubtotal;

                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $qty,
                    'unit_price' => $product->selling_price,
                    'purchase_price' => $product->purchase_price,
                    'discount' => $itemDiscount,
                    'subtotal' => $itemSubtotal,
                ]);

                $this->stockService->decrementStock(
                    productId: $product->id,
                    quantity: $qty,
                    userId: $userId,
                    referenceType: 'transaction',
                    referenceId: $transaction->id,
                    note: "Sale #{$invoiceNumber}"
                );
            }

            $total = $subtotal - $discount + $tax;
            $changeAmount = $paidAmount - $total;

            // Mencegah kurang bayar untuk semua metode
            if ($changeAmount < 0) {
                throw new Exception("Jumlah bayar kurang dari total tagihan.");
            }
            
            // Untuk metode non-tunai, bayar harus pas (tidak boleh ada kembalian)
            if ($paymentMethod !== 'cash' && $changeAmount > 0) {
                throw new Exception("Pembayaran non-tunai harus berjumlah pas dengan total tagihan.");
            }

            $transaction->update([
                'subtotal' => $subtotal,
                'total' => $total,
                'change_amount' => $paymentMethod === 'cash' ? $changeAmount : 0,
            ]);

            if ($paymentMethod === 'cash') {
                $this->cashService->recordIn(
                    userId: $userId,
                    amount: $total, // Hanya merekam nominal uang yang menjadi hak toko
                    description: "Penjualan #{$invoiceNumber}",
                    referenceType: 'sale',
                    referenceId: $transaction->id
                );
            }

            return $transaction;
        });
    }

    /**
     * Void a transaction
     */
    public function voidTransaction(Transaction $transaction, int $userId): void
    {
        if ($transaction->status === TransactionStatus::VOIDED) {
            throw new Exception("Transaction is already voided.");
        }

        DB::transaction(function () use ($transaction, $userId) {
            $transaction->update(['status' => TransactionStatus::VOIDED]);

            foreach ($transaction->transactionDetails as $detail) {
                // Return stock
                $this->stockService->incrementStock(
                    productId: $detail->product_id,
                    quantity: $detail->quantity,
                    userId: $userId,
                    type: StockMovementType::RETURN,
                    referenceType: 'void_transaction',
                    referenceId: $transaction->id,
                    note: "Void Sale #{$transaction->invoice_number}"
                );
            }

            // Reverse Cash Transaction if it was cash
            if ($transaction->payment_method === 'cash') {
                $this->cashService->recordOut(
                    userId: $userId,
                    amount: $transaction->total,
                    description: "Void Penjualan #{$transaction->invoice_number}",
                    referenceType: 'void_sale',
                    referenceId: $transaction->id
                );
            }
        });
    }

    private function generateInvoiceNumber(Carbon $date): string
    {
        $prefix = "INV-{$date->format('Ymd')}-";
        
        $latest = Transaction::where('invoice_number', 'LIKE', "{$prefix}%")
            ->orderBy('id', 'desc')
            ->first();

        if (!$latest) {
            return $prefix . '0001';
        }

        $lastNumber = (int) str_replace($prefix, '', $latest->invoice_number);
        $nextNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return $prefix . $nextNumber;
    }
}
