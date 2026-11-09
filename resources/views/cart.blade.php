<x-layouts.store title="السلة">
    <h1 class="squiggle mb-8 text-4xl">سلتك</h1>

    @if ($lines->isEmpty())
        <div class="card py-16 text-center">
            <p class="text-5xl" aria-hidden="true">🧺</p><p class="mt-3 font-display text-2xl font-bold">سلتك فاضية</p>
            <p class="mt-1 text-muted">تصفّح المنتجات وأضف اللي يعجبك.</p>
            <a href="{{ route('shop') }}" class="btn-primary mt-5">ابدأ التسوّق</a>
        </div>
    @else
        <div class="grid gap-6 lg:grid-cols-3">
            <ul class="space-y-3 lg:col-span-2">
                @foreach ($lines as $line)
                    <li class="card flex gap-4">
                        <a href="{{ route('products.show', $line['product']) }}" class="h-24 w-24 shrink-0 overflow-hidden bg-sky-soft">
                            <x-product-image :product="$line['product']" class="h-full w-full" />
                        </a>
                        <div class="flex flex-1 flex-col gap-2">
                            <div class="flex justify-between gap-3">
                                <a href="{{ route('products.show', $line['product']) }}" class="font-bold hover:text-brand">{{ $line['product']->name }}</a>
                                <x-price :amount="$line['line_total']" class="shrink-0 font-bold" />
                            </div>
                            <x-price :amount="$line['product']->price" class="text-sm text-muted" />
                            <div class="mt-auto flex items-center gap-3">
                                <form method="POST" action="{{ route('cart.update', $line['product']) }}" class="flex items-center gap-2">
                                    @csrf
                                    @method('PATCH')
                                    <label for="qty-{{ $line['product']->id }}" class="sr-only">الكمية</label>
                                    <select id="qty-{{ $line['product']->id }}" name="quantity" class="input w-20 rounded-full py-1.5 text-sm" onchange="this.form.submit()">
                                        @foreach (range(1, max(1, min(\App\Services\Cart::MAX_QUANTITY_PER_ITEM, $line['product']->stock))) as $quantity)
                                            <option value="{{ $quantity }}" @selected($quantity === $line['quantity'])>{{ $quantity }}</option>
                                        @endforeach
                                    </select>
                                    <noscript><button class="text-sm font-bold text-brand">تحديث</button></noscript>
                                </form>
                                <form method="POST" action="{{ route('cart.destroy', $line['product']) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-sm font-bold text-rose-600 hover:underline">حذف</button>
                                </form>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>

            @include('partials.order-summary', ['action' => true])
        </div>
    @endif
</x-layouts.store>
