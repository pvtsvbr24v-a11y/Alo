<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

/**
 * رموز الدخول برقم الجوال: ٤ أرقام، صالحة ٥ دقائق، و٥ محاولات بالكثير.
 */
class PhoneOtp
{
    public const LENGTH = 4;

    public const MINUTES_VALID = 5;

    public const MAX_ATTEMPTS = 5;

    public const RESEND_SECONDS = 60;

    /**
     * رمز ثابت للاختبارات فقط.
     */
    private static ?string $fixedCode = null;

    public function __construct(private SmsSender $sms) {}

    public static function useFixedCode(?string $code): void
    {
        self::$fixedCode = $code;
    }

    public function send(string $phone): void
    {
        $code = self::$fixedCode ?? str_pad((string) random_int(0, 9999), self::LENGTH, '0', STR_PAD_LEFT);

        Cache::put($this->key($phone), ['hash' => Hash::make($code), 'attempts' => 0], now()->addMinutes(self::MINUTES_VALID));
        Cache::put($this->key($phone).':sent', true, now()->addSeconds(self::RESEND_SECONDS));

        $this->sms->send($phone, "رمز الدخول لمتجر Alo: {$code}");
    }

    public function canResend(string $phone): bool
    {
        return ! Cache::has($this->key($phone).':sent');
    }

    public function verify(string $phone, string $code): bool
    {
        /** @var array{hash: string, attempts: int}|null $entry */
        $entry = Cache::get($this->key($phone));

        if ($entry === null || $entry['attempts'] >= self::MAX_ATTEMPTS) {
            return false;
        }

        if (! Hash::check($code, $entry['hash'])) {
            $entry['attempts']++;
            Cache::put($this->key($phone), $entry, now()->addMinutes(self::MINUTES_VALID));

            return false;
        }

        Cache::forget($this->key($phone));

        return true;
    }

    /**
     * يحول أي صيغة (+9665..., 9665..., 5...) إلى 05XXXXXXXX، أو null إذا الرقم غير صحيح.
     */
    public static function normalize(string $phone): ?string
    {
        $digits = preg_replace('/\D/', '', strtr($phone, ['٠' => '0', '١' => '1', '٢' => '2', '٣' => '3', '٤' => '4', '٥' => '5', '٦' => '6', '٧' => '7', '٨' => '8', '٩' => '9']));

        $digits = match (true) {
            str_starts_with($digits, '00966') => '0'.substr($digits, 5),
            str_starts_with($digits, '966') => '0'.substr($digits, 3),
            str_starts_with($digits, '5') => '0'.$digits,
            default => $digits,
        };

        return preg_match('/^05\d{8}$/', $digits) === 1 ? $digits : null;
    }

    private function key(string $phone): string
    {
        return 'otp:'.$phone;
    }
}
