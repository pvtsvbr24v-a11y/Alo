<x-layouts.store title="إتمام الطلب">
    <h1 class="squiggle mb-8 text-4xl">إتمام الطلب</h1>

    <form method="POST" action="{{ route('checkout.store') }}" class="grid gap-6 lg:grid-cols-3">
        @csrf
        <div class="space-y-6 lg:col-span-2">
            <section class="card space-y-4" aria-labelledby="shipping-title">
                <h2 id="shipping-title" class="text-2xl">وين نوصّل حكايتك؟</h2>

                <div>
                    <label for="customer_name" class="label">الاسم</label>
                    <input id="customer_name" name="customer_name" class="input" autocomplete="name" required value="{{ old('customer_name', auth()->user()->name ?? $lastOrder?->customer_name) }}">
                    @error('customer_name')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <p class="label">رقم الجوال</p>
                    <p class=" bg-sky-soft px-4 py-3" dir="ltr" style="text-align: right">{{ auth()->user()->phone }}</p>
                </div>

                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="city" class="label">المدينة</label>
                        <select id="city" name="city" class="input" required>
                            <option value="">اختر المدينة</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city }}" @selected(old('city', auth()->user()->city ?? $lastOrder?->city) === $city)>{{ $city }}</option>
                            @endforeach
                        </select>
                        @error('city')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="district" class="label">الحي</label>
                        <input id="district" name="district" class="input" value="{{ old('district', auth()->user()->district ?? $lastOrder?->district) }}">
                        @error('district')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div>
                    <label for="address" class="label">العنوان بالتفصيل</label>
                    <input id="address" name="address" class="input" autocomplete="street-address" required placeholder="الشارع، رقم المبنى، أو العنوان الوطني" value="{{ old('address', auth()->user()->address ?? $lastOrder?->address) }}">
                    @error('address')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label for="notes" class="label">ملاحظات (اختياري)</label>
                    <textarea id="notes" name="notes" rows="2" class="input">{{ old('notes') }}</textarea>
                    @error('notes')<p class="field-error">{{ $message }}</p>@enderror
                </div>
            </section>

            <fieldset class="card space-y-3">
                <legend class="sr-only">طريقة الدفع</legend>
                <p class="font-display text-2xl font-bold">طريقة الدفع</p>
                @php($selectedMethod = old('payment_method', $paymentMethods[0]->value ?? null))
                @forelse ($paymentMethods as $method)
                    <label class="flex cursor-pointer items-center gap-4 border border-sand p-4 transition has-[:checked]:border-brand has-[:checked]:bg-cream">
                        <input type="radio" name="payment_method" value="{{ $method->value }}" class="accent-brand" @checked($selectedMethod === $method->value)>
                        <x-payment-badge :method="$method" />
                        <span>
                            <span class="block font-bold">{{ $method->label() }}</span>
                            <span class="block text-xs text-muted">{{ $method->hint() }}</span>
                        </span>
                    </label>
                @empty
                    <p class="text-sm text-muted">ما فيه طرق دفع مفعّلة حالياً.</p>
                @endforelse
                @error('payment_method')<p class="field-error">{{ $message }}</p>@enderror
            </fieldset>

            <section class="card" aria-labelledby="items-title">
                <h2 id="items-title" class="mb-3 text-2xl">المنتجات ({{ $lines->sum('quantity') }})</h2>
                <ul class="divide-y divide-ink/10">
                    @foreach ($lines as $line)
                        <li class="flex justify-between gap-3 py-2 text-sm">
                            <span>{{ $line['product']->name }} × {{ $line['quantity'] }}</span>
                            <x-price :amount="$line['line_total']" />
                        </li>
                    @endforeach
                </ul>
            </section>
        </div>

        <div class="space-y-3">
            @include('partials.order-summary')
            <button type="submit" class="btn-primary w-full py-4 text-base">تأكيد الطلب 🤍</button>
        </div>
    </form>
</x-layouts.store>
