<?php

namespace App\Enums;

enum OrderStatus: string
{
    case AwaitingPayment = 'awaiting_payment';
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Shipped = 'shipped';
    case Delivered = 'delivered';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::AwaitingPayment => 'بانتظار الدفع',
            self::Pending => 'بانتظار التأكيد',
            self::Confirmed => 'تم التأكيد',
            self::Shipped => 'تم الشحن',
            self::Delivered => 'تم التوصيل',
            self::Cancelled => 'ملغي',
        };
    }

    /**
     * كلاسات Tailwind لشارة الحالة.
     */
    public function badgeClasses(): string
    {
        return match ($this) {
            self::AwaitingPayment => 'bg-slate-100 text-slate-700',
            self::Pending => 'bg-amber-100 text-amber-800',
            self::Confirmed => 'bg-sky-100 text-sky-800',
            self::Shipped => 'bg-violet-100 text-violet-800',
            self::Delivered => 'bg-emerald-100 text-emerald-800',
            self::Cancelled => 'bg-rose-100 text-rose-800',
        };
    }
}
