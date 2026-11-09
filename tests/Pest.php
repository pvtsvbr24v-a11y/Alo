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
        Http::fake();
        $this->withoutVite();
        PhoneOtp::useFixedCode('1234');
    })
    ->afterEach(function () {
        PhoneOtp::useFixedCode(null);
    })
    ->in('Feature');
