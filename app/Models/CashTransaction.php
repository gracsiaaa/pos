<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Enums\CashTransactionType;

class CashTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'amount',
        'description',
        'reference_type',
        'reference_id',
        'date',
    ];

    protected $casts = [
        'type' => CashTransactionType::class,
        'amount' => 'decimal:2',
        'date' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
