<?php

namespace App\Policies;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Auth\Access\Response;
use App\Enums\RoleEnum;

class TransactionPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Transaction $transaction): bool
    {
        // Cashier can only view their own transactions, Admin/Owner can view all
        if ($user->role->name === RoleEnum::CASHIER->value) {
            return $user->id === $transaction->user_id;
        }
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Only Cashiers (or Admins testing) can create POS transactions usually.
        return in_array($user->role->name, [RoleEnum::CASHIER->value, RoleEnum::ADMIN->value]);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Transaction $transaction): bool
    {
        return false; // Transactions are immutable once created, only voided
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Transaction $transaction): bool
    {
        return false; // Transactions should never be deleted, only voided
    }

    /**
     * Void a transaction (Custom action)
     */
    public function void(User $user, Transaction $transaction): bool
    {
        return $user->role->name === RoleEnum::ADMIN->value;
    }
}
