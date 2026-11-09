<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * يرسل رسائل SMS عبر المزود المحدد في SMS_DRIVER.
 * log: الرسالة تنكتب في السجل فقط (للتجربة قبل الاشتراك مع مزود).
 */
class SmsSender
{
    public function send(string $phone, string $message): bool
    {
        $international = '966'.substr($phone, 1);

        return match (config('services.sms.driver')) {
            'taqnyat' => Http::withToken((string) config('services.sms.token'))
                ->post('https://api.taqnyat.sa/v1/messages', [
                    'recipients' => [$international],
                    'body' => $message,
                    'sender' => config('services.sms.sender'),
                ])->successful(),
            'msegat' => Http::post('https://www.msegat.com/gw/sendsms.php', [
                'userName' => config('services.sms.username'),
                'apiKey' => config('services.sms.token'),
                'numbers' => $international,
                'userSender' => config('services.sms.sender'),
                'msg' => $message,
            ])->successful(),
            'unifonic' => Http::asForm()->post('https://el.cloud.unifonic.com/rest/SMS/messages', [
                'AppSid' => config('services.sms.token'),
                'SenderID' => config('services.sms.sender'),
                'Recipient' => $international,
                'Body' => $message,
            ])->successful(),
            default => $this->log($phone, $message),
        };
    }

    private function log(string $phone, string $message): bool
    {
        Log::info("SMS إلى {$phone}: {$message}");

        return true;
    }
}
