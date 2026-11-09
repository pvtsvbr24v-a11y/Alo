<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case CashOnDelivery = 'cod';
    case BankTransfer = 'bank_transfer';

    public function label(): string
    {
        return match ($this) {
            self::CashOnDelivery => 'الدفع عند الاستلام',
            self::BankTransfer => 'تحويل بنكي',
        };
    }
}
