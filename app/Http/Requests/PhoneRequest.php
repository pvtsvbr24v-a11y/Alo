<?php

namespace App\Http\Requests;

use App\Services\PhoneOtp;
use Illuminate\Foundation\Http\FormRequest;

class PhoneRequest extends FormRequest
{
    /**
     * نحول الرقم لصيغة 05XXXXXXXX قبل التحقق.
     */
    protected function prepareForValidation(): void
    {
        $this->merge(['phone' => PhoneOtp::normalize((string) $this->input('phone')) ?? $this->input('phone')]);
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'phone' => ['required', 'regex:/^05\d{8}$/'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'phone.required' => 'اكتب رقم جوالك',
            'phone.regex' => 'رقم الجوال لازم يبدأ بـ 05 ويكون 10 أرقام',
        ];
    }
}
