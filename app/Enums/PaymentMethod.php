<?php

namespace App\Enums;

enum PaymentMethod: string
{
    case Mada = 'mada';
    case ApplePay = 'apple_pay';
    case Tabby = 'tabby';
    case Tamara = 'tamara';
    case CashOnDelivery = 'cod';
    case BankTransfer = 'bank_transfer';

    public function label(): string
    {
        return match ($this) {
            self::Mada => 'مدى',
            self::ApplePay => 'Apple Pay',
            self::Tabby => 'تابي',
            self::Tamara => 'تمارا',
            self::CashOnDelivery => 'الدفع عند الاستلام',
            self::BankTransfer => 'تحويل بنكي',
        };
    }

    /**
     * وصف قصير تحت اسم الطريقة في صفحة الدفع.
     */
    public function hint(): string
    {
        return match ($this) {
            self::Mada => 'بطاقة مدى أو فيزا أو ماستركارد',
            self::ApplePay => 'ادفع بلمسة من جوالك',
            self::Tabby => 'قسّمها على ٤ دفعات بدون فوائد',
            self::Tamara => 'قسّمها على دفعات بدون فوائد',
            self::CashOnDelivery => 'ادفع لما يوصلك الطلب',
            self::BankTransfer => 'حوّل المبلغ وأرسل الإيصال',
        };
    }

    /**
     * الطرق اللي تحتاج بوابة دفع إلكترونية.
     */
    public function isOnline(): bool
    {
        return in_array($this, [self::Mada, self::ApplePay, self::Tabby, self::Tamara], true);
    }

    /**
     * البوابة اللي تنفذ هالطريقة (مدى وApple Pay عن طريق ميسّر).
     */
    public function gateway(): ?string
    {
        return match ($this) {
            self::Mada, self::ApplePay => 'moyasar',
            self::Tabby => 'tabby',
            self::Tamara => 'tamara',
            default => null,
        };
    }

    /**
     * الطرق اللي تظهر للعميل: الإلكترونية لما تنحط مفاتيحها، والباقي حسب الإعدادات.
     *
     * @return list<self>
     */
    public static function available(): array
    {
        return array_values(array_filter(self::cases(), fn (self $method): bool => match ($method) {
            self::CashOnDelivery => (bool) config('payments.cash_on_delivery'),
            self::BankTransfer => (bool) config('payments.bank_transfer'),
            default => filled(config("payments.{$method->gateway()}.secret_key")),
        }));
    }
}
