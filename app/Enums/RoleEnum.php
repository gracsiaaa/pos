<?php

namespace App\Enums;

enum RoleEnum: string
{
    case ADMIN = 'admin';
    case CASHIER = 'cashier';
    case OWNER = 'owner';

    public function label(): string
    {
        return match($this) {
            self::ADMIN => 'Administrator',
            self::CASHIER => 'Kasir',
            self::OWNER => 'Owner / Manager',
        };
    }
}
