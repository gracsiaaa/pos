<?php

namespace App\Services;

use App\Models\Product;
use App\Models\StockMovement;
use App\Enums\StockMovementType;
use Illuminate\Support\Facades\DB;
use Exception;

class StockService
{
    /**
     * Decrease product stock.
     */
    public function decrementStock(int $productId, int $quantity, int $userId, string $referenceType = null, int $referenceId = null, string $note = null): StockMovement
    {
        if ($quantity <= 0) {
            throw new Exception("Quantity must be greater than 0");
        }

        // Lock row for update to prevent race conditions
        $product = Product::where('id', $productId)->lockForUpdate()->firstOrFail();

        if ($product->stock < $quantity) {
            throw new Exception("Insufficient stock for product: {$product->name}");
        }

        $stockBefore = $product->stock;
        $stockAfter = $stockBefore - $quantity;

        $product->stock = $stockAfter;
        $product->save();

        return StockMovement::create([
            'product_id' => $product->id,
            'user_id' => $userId,
            'type' => StockMovementType::OUT,
            'quantity' => $quantity,
            'stock_before' => $stockBefore,
            'stock_after' => $stockAfter,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'note' => $note,
        ]);
    }

    /**
     * Increase product stock.
     */
    public function incrementStock(int $productId, int $quantity, int $userId, StockMovementType $type = StockMovementType::IN, string $referenceType = null, int $referenceId = null, string $note = null): StockMovement
    {
        if ($quantity <= 0) {
            throw new Exception("Quantity must be greater than 0");
        }

        $product = Product::where('id', $productId)->lockForUpdate()->firstOrFail();

        $stockBefore = $product->stock;
        $stockAfter = $stockBefore + $quantity;

        $product->stock = $stockAfter;
        $product->save();

        return StockMovement::create([
            'product_id' => $product->id,
            'user_id' => $userId,
            'type' => $type,
            'quantity' => $quantity,
            'stock_before' => $stockBefore,
            'stock_after' => $stockAfter,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'note' => $note,
        ]);
    }
}
