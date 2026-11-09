<x-layouts.store title="تسجيل الدخول">
    <div class="mx-auto max-w-sm">
        <div class="card space-y-5 p-8">
            <div class="text-center">
                                <h1 class="text-3xl">أهلاً فيك</h1>
                <p class="mt-1 text-sm text-muted">اكتب رقم جوالك ونرسل لك رمز دخول</p>
            </div>
            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <div>
                    <label for="phone" class="label">رقم الجوال</label>
                    <input id="phone" name="phone" type="tel" inputmode="numeric" autocomplete="tel" dir="ltr" placeholder="05XXXXXXXX" class="input text-center text-lg tracking-widest" required autofocus value="{{ old('phone') }}">
                    @error('phone')<p class="field-error">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="btn-primary w-full">أرسل الرمز</button>
            </form>
        </div>
    </div>
</x-layouts.store>
