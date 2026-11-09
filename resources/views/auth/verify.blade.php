<x-layouts.store title="رمز التحقق">
    <div class="mx-auto max-w-sm">
        <div class="card space-y-5 p-8">
            <div class="text-center">
                <h1 class="text-2xl font-extrabold">اكتب الرمز</h1>
                <p class="mt-1 text-sm text-muted">أرسلنا رمز من {{ \App\Services\PhoneOtp::LENGTH }} أرقام إلى <span dir="ltr" class="font-bold text-ink">{{ $phone }}</span></p>
            </div>
            <form method="POST" action="{{ route('login.verify') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="code" class="sr-only">رمز التحقق</label>
                    <input id="code" name="code" type="text" inputmode="numeric" autocomplete="one-time-code" maxlength="{{ \App\Services\PhoneOtp::LENGTH }}" dir="ltr" placeholder="••••" class="input text-center text-2xl tracking-[0.75em]" required autofocus>
                    @error('code')<p class="field-error">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="btn-primary w-full">دخول</button>
            </form>
            <div class="flex items-center justify-between text-sm">
                <a href="{{ route('login') }}" class="font-bold text-muted hover:text-ink">تغيير الرقم</a>
                <form method="POST" action="{{ route('login.resend') }}">
                    @csrf
                    <button type="submit" class="font-bold text-brand disabled:text-muted" data-resend-after="{{ $resendSeconds }}"><span data-resend-label>إعادة الإرسال</span></button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.store>
