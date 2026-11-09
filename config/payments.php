<?php

/*
|--------------------------------------------------------------------------
| طرق الدفع
|--------------------------------------------------------------------------
| كل طريقة إلكترونية تظهر في صفحة الدفع أول ما تنحط مفاتيحها في
| Environment variables داخل Laravel Cloud.
|
| مدى و Apple Pay: من حسابك في ميسّر (moyasar.com)
| تابي: من حساب التاجر في تابي (tabby.ai)
| تمارا: من حساب التاجر في تمارا (tamara.co)
*/

return [

    'moyasar' => [
        'secret_key' => env('MOYASAR_SECRET_KEY'),
        'base_url' => 'https://api.moyasar.com/v1',
    ],

    'tabby' => [
        'secret_key' => env('TABBY_SECRET_KEY'),
        'merchant_code' => env('TABBY_MERCHANT_CODE'),
        'base_url' => 'https://api.tabby.ai/api/v2',
    ],

    'tamara' => [
        'secret_key' => env('TAMARA_API_TOKEN'),
        'base_url' => env('TAMARA_BASE_URL', 'https://api.tamara.co'),
    ],

    // طرق بدون بوابة
    'cash_on_delivery' => (bool) env('PAYMENT_COD_ENABLED', true),
    'bank_transfer' => (bool) env('PAYMENT_BANK_TRANSFER_ENABLED', false),
];
