<x-layouts.store title="حسابي">
    {{-- رأس الصفحة: الأفتار والاسم --}}
    <section class="card relative overflow-hidden text-center sm:text-start">
        <div class="stripes absolute inset-x-0 top-0 h-24 opacity-60" aria-hidden="true"></div>
        <div class="relative flex flex-col items-center gap-5 pt-10 sm:flex-row sm:items-end">
            <x-avatar :user="$user" size="h-28 w-28 text-5xl" class="ring-4" />
            <div class="flex-1">
                <h1 class="text-4xl">{{ $user->name ?: 'أهلاً فيك 🤍' }}</h1>
                <p class="mt-1 text-muted" dir="ltr" style="text-align: inherit">{{ $user->phone }}</p>
            </div>
            <div class="flex gap-3">
                <div class="glass rounded-2xl px-5 py-3 text-center">
                    <p class="font-latin text-3xl leading-none font-semibold">{{ $ordersCount }}</p>
                    <p class="mt-1 text-xs text-muted">طلب</p>
                </div>
                <div class="glass rounded-2xl px-5 py-3 text-center">
                    <p class="font-latin text-lg leading-none font-semibold">{{ $user->created_at->translatedFormat('M Y') }}</p>
                    <p class="mt-1 text-xs text-muted">معنا من</p>
                </div>
            </div>
        </div>
    </section>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <div class="space-y-6 lg:col-span-2">
            {{-- المعلومات والعنوان --}}
            <form method="POST" action="{{ route('account.update') }}" class="card space-y-4">
                @csrf
                @method('PATCH')
                <h2 class="text-2xl">معلوماتي</h2>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="name" class="label">الاسم</label>
                        <input id="name" name="name" class="input" autocomplete="name" required value="{{ old('name', $user->name) }}">
                        @error('name')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <p class="label">رقم الجوال</p>
                        <p class="input text-muted" dir="ltr" style="text-align: right">{{ $user->phone }}</p>
                    </div>
                </div>

                <h3 class="pt-2 text-xl">عنوان التوصيل</h3>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="city" class="label">المدينة</label>
                        <select id="city" name="city" class="input">
                            <option value="">اختر المدينة</option>
                            @foreach ($cities as $city)
                                <option value="{{ $city }}" @selected(old('city', $user->city) === $city)>{{ $city }}</option>
                            @endforeach
                        </select>
                        @error('city')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label for="district" class="label">الحي</label>
                        <input id="district" name="district" class="input" value="{{ old('district', $user->district) }}">
                        @error('district')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div>
                    <label for="address" class="label">العنوان بالتفصيل</label>
                    <input id="address" name="address" class="input" autocomplete="street-address" placeholder="الشارع، رقم المبنى، أو العنوان الوطني" value="{{ old('address', $user->address) }}">
                    @error('address')<p class="field-error">{{ $message }}</p>@enderror
                </div>
                <button type="submit" class="btn-primary">حفظ 🤍</button>
            </form>

            {{-- آخر الطلبات --}}
            <section class="card" aria-labelledby="orders-title">
                <div class="mb-4 flex items-center justify-between">
                    <h2 id="orders-title" class="text-2xl">طلباتي</h2>
                    @if ($ordersCount > $orders->count())
                        <a href="{{ route('orders.index') }}" class="rounded-full px-4 py-2 text-sm font-bold text-brand hover:bg-rose-soft">كل الطلبات ←</a>
                    @endif
                </div>
                @forelse ($orders as $order)
                    <a href="{{ route('orders.show', $order) }}" class="mb-3 flex flex-wrap items-center justify-between gap-3 rounded-2xl bg-white/50 px-4 py-3 transition last:mb-0 hover:bg-white/80">
                        <div>
                            <p class="font-bold" dir="ltr">{{ $order->number }}</p>
                            <p class="text-xs text-muted">{{ $order->created_at->translatedFormat('j F Y') }} · {{ $order->items_count }} منتجات</p>
                        </div>
                        <span class="rounded-full px-3 py-1 text-xs font-bold {{ $order->status->badgeClasses() }}">{{ $order->status->label() }}</span>
                        <x-price :amount="$order->total" class="font-bold" />
                    </a>
                @empty
                    <div class="py-8 text-center">
                        <p class="text-4xl" aria-hidden="true">🧺</p>
                        <p class="mt-2 text-muted">ما عندك طلبات للحين</p>
                        <a href="{{ route('shop') }}" class="btn-soft mt-4">ابدأ التسوّق</a>
                    </div>
                @endforelse
            </section>
        </div>

        {{-- الأفتار --}}
        <aside class="card h-fit space-y-5" aria-labelledby="avatar-title">
            <h2 id="avatar-title" class="text-2xl">الأفتار</h2>

            <form method="POST" action="{{ route('account.avatar') }}" class="space-y-3">
                @csrf
                <p class="text-sm text-muted">اختر واحد:</p>
                <div class="grid grid-cols-4 gap-2">
                    @foreach ($presetAvatars as $preset)
                        <button type="submit" name="preset" value="{{ $preset }}" class="glass flex aspect-square items-center justify-center rounded-2xl text-2xl transition hover:-translate-y-1 {{ $user->avatar === $preset ? 'ring-4 ring-sky' : '' }}" aria-label="اختيار الأفتار {{ $preset }}">{{ $preset }}</button>
                    @endforeach
                </div>
                @error('preset')<p class="field-error">{{ $message }}</p>@enderror
            </form>

            <form method="POST" action="{{ route('account.avatar') }}" enctype="multipart/form-data" class="space-y-3 border-t-2 border-dashed border-sky/40 pt-5">
                @csrf
                <label for="avatar_file" class="label">أو ارفع صورتك</label>
                <input id="avatar_file" name="avatar_file" type="file" accept="image/*" class="block w-full text-sm file:me-3 file:rounded-full file:border-0 file:bg-sky-soft file:px-4 file:py-2 file:font-bold file:text-ink" required>
                <p class="text-xs text-muted">أقل من نص ميجا</p>
                @error('avatar_file')<p class="field-error">{{ $message }}</p>@enderror
                <button type="submit" class="btn-soft w-full">رفع الصورة</button>
            </form>

            @if ($user->avatar)
                <form method="POST" action="{{ route('account.avatar.destroy') }}">
                    @csrf
                    @method('DELETE')
                    <button class="w-full text-sm font-bold text-muted hover:text-brand-dark">شيل الأفتار</button>
                </form>
            @endif

            <form method="POST" action="{{ route('logout') }}" class="border-t-2 border-dashed border-sky/40 pt-5">
                @csrf
                <button class="btn-outline w-full">تسجيل الخروج</button>
            </form>
        </aside>
    </div>
</x-layouts.store>
