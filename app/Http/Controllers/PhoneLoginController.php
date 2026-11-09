<?php

namespace App\Http\Controllers;

use App\Http\Requests\PhoneRequest;
use App\Models\User;
use App\Services\PhoneOtp;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PhoneLoginController extends Controller
{
    public function __construct(private PhoneOtp $otp) {}

    public function show(): View
    {
        return view('auth.phone');
    }

    public function sendCode(PhoneRequest $request): RedirectResponse
    {
        $phone = $request->validated('phone');

        if ($this->otp->canResend($phone)) {
            $this->otp->send($phone);
        }

        $request->session()->put('login_phone', $phone);

        return redirect()->route('login.verify');
    }

    public function showVerify(Request $request): View|RedirectResponse
    {
        if (! $request->session()->has('login_phone')) {
            return redirect()->route('login');
        }

        return view('auth.verify', [
            'phone' => $request->session()->get('login_phone'),
            'resendSeconds' => PhoneOtp::RESEND_SECONDS,
        ]);
    }

    public function verify(Request $request): RedirectResponse
    {
        $phone = $request->session()->get('login_phone');

        if ($phone === null) {
            return redirect()->route('login');
        }

        $code = $request->validate(
            ['code' => ['required', 'digits:'.PhoneOtp::LENGTH]],
            ['code.required' => 'اكتب الرمز', 'code.digits' => 'الرمز '.PhoneOtp::LENGTH.' أرقام'],
        )['code'];

        if (! $this->otp->verify($phone, $code)) {
            return back()->withErrors(['code' => 'الرمز غير صحيح أو انتهت صلاحيته']);
        }

        $user = User::query()->firstOrCreate(['phone' => $phone]);
        $user->forceFill(['phone_verified_at' => now()])->save();

        Auth::login($user, true);
        $request->session()->forget('login_phone');
        $request->session()->regenerate();

        return redirect()->intended(route('home'));
    }

    public function resend(Request $request): RedirectResponse
    {
        $phone = $request->session()->get('login_phone');

        if ($phone === null) {
            return redirect()->route('login');
        }

        if (! $this->otp->canResend($phone)) {
            return back()->withErrors(['code' => 'انتظر شوي قبل ما تطلب رمز جديد']);
        }

        $this->otp->send($phone);

        return back()->with('status', 'أرسلنا رمز جديد');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
