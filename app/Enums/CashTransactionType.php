<?php

namespace App\Enums;

enum CashTransactionType: string
{
    case IN = 'in';
    case OUT = 'out';
}
