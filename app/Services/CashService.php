<?php

namespace App\Services;

use App\Models\CashTransaction;
use App\Enums\CashTransactionType;
use Exception;

class CashService
{
    public function recordIn(int $userId, float $amount, string $description, string $referenceType = null, int $referenceId = null): CashTransaction
    {
        if ($amount <= 0) {
            throw new Exception("Amount must be greater than 0");
        }

        return CashTransaction::create([
            'user_id' => $userId,
            'type' => CashTransactionType::IN,
            'amount' => $amount,
            'description' => $description,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'date' => now()->toDateString(),
        ]);
    }

    public function recordOut(int $userId, float $amount, string $description, string $referenceType = null, int $referenceId = null): CashTransaction
    {
        if ($amount <= 0) {
            throw new Exception("Amount must be greater than 0");
        }

        return CashTransaction::create([
            'user_id' => $userId,
            'type' => CashTransactionType::OUT,
            'amount' => $amount,
            'description' => $description,
            'reference_type' => $referenceType,
            'reference_id' => $referenceId,
            'date' => now()->toDateString(),
        ]);
    }
}
