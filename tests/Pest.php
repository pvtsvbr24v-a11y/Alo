<?php

use App\Services\PhoneOtp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

/*
|--------------------------------------------------------------------------
| إعداد الاختبارات
|--------------------------------------------------------------------------
| كل اختبار في Feature يبدأ بقاعدة بيانات فاضية، بدون Vite، وبدون إنترنت،
| ورمز الدخول ثابت 1234.
*/

pest()->extend(TestCase::class)
    ->use(RefreshDatabase::class)
    ->beforeEach(function () {
        Http::preventStrayRequests();
        $this->withoutVite();
        PhoneOtp::useFixedCode('1234');
        // الاختبارات القديمة تطلب بالدفع عند الاستلام
        config(['payments.cash_on_delivery' => true]);
    })
    ->afterEach(function () {
        PhoneOtp::useFixedCode(null);
    })
    ->in('Feature');
